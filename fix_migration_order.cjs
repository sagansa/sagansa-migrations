const fs = require('fs');
const path = require('path');

const dir = __dirname + '/database/migrations';

// Get all php files in the directory (non-recursive, main dir only)
const files = fs.readdirSync(dir).filter(f => f.endsWith('.php') && !f.startsWith('_')).sort();

// For each file, find the table being created and foreign key references
const migrations = [];

for (const file of files) {
    const content = fs.readFileSync(path.join(dir, file), 'utf8');

    // Find table being created: Schema::create('table_name', ...)
    const createMatch = content.match(/Schema::create\(\s*['"](\w+)['"]/);
    const tableName = createMatch ? createMatch[1] : null;

    // Find foreign key references: ->on('table_name')
    const fkMatches = [...content.matchAll(/->on\(\s*['"](\w+)['"]\s*\)/g)];
    const references = fkMatches.map(m => m[1]).filter(t => t !== tableName);
    const uniqueRefs = [...new Set(references)];

    migrations.push({ file, tableName, references: uniqueRefs });
}

// Build table-to-file mapping
const tableToFile = {};
for (const m of migrations) {
    if (m.tableName) {
        tableToFile[m.tableName] = m.file;
    }
}

// For each migration, find which referenced tables are created by later files
const issues = [];
for (const m of migrations) {
    for (const ref of m.references) {
        const refFile = tableToFile[ref];
        if (refFile && refFile > m.file) {
            issues.push({
                migration: m.file,
                table: m.tableName,
                referencedTable: ref,
                referencedBy: refFile
            });
        }
    }
}

console.log('=== ORDERING ISSUES FOUND ===\n');
for (const issue of issues) {
    console.log(`  ${issue.migration} (creates "${issue.table}") references "${issue.referencedTable}" → created later by ${issue.referencedBy}`);
}

console.log(`\nTotal issues: ${issues.length}`);

// Now let's do topological sort to find correct order
// Build adjacency list
const fileIndex = {};
migrations.forEach((m, i) => fileIndex[m.file] = i);

const adj = migrations.map(() => []);
for (const m of migrations) {
    const mi = fileIndex[m.file];
    for (const ref of m.references) {
        const refFile = tableToFile[ref];
        if (refFile && refFile !== m.file) {
            const ri = fileIndex[refFile];
            // refFile must come before m.file
            adj[ri].push(mi);
        }
    }
}

// Topological sort using Kahn's algorithm
const inDegree = migrations.map((_, i) => 0);
for (let i = 0; i < adj.length; i++) {
    for (const j of adj[i]) {
        inDegree[j]++;
    }
}

const queue = [];
for (let i = 0; i < inDegree.length; i++) {
    if (inDegree[i] === 0) queue.push(i);
}

const sorted = [];
while (queue.length > 0) {
    // Sort queue to maintain relative order of existing timestamps where possible
    queue.sort((a, b) => migrations[a].file.localeCompare(migrations[b].file));
    const node = queue.shift();
    sorted.push(node);
    for (const j of adj[node]) {
        inDegree[j]--;
        if (inDegree[j] === 0) queue.push(j);
    }
}

if (sorted.length !== migrations.length) {
    console.log('\nERROR: Cycle detected! Cannot resolve ordering.');
    console.log('Files not sorted:', migrations.length - sorted.length);
    process.exit(1);
}

// Generate new filenames with sequential timestamps
// Use a base timestamp and increment seconds
const baseDate = '2024_07_01';
let hour = 0;
let minute = 0;
let second = 1;

function nextTimestamp() {
    const ts = `${baseDate}_${hour.toString().padStart(2, '0')}${minute.toString().padStart(2, '0')}${second.toString().padStart(2, '0')}`;
    second++;
    if (second > 59) {
        second = 0;
        minute++;
        if (minute > 59) {
            minute = 0;
            hour++;
        }
    }
    return ts;
}

// For files that don't create tables (alter tables), keep their relative position
// but don't assign new timestamps unless needed
const newOrder = sorted.map(i => migrations[i].file);

console.log('\n=== CORRECT MIGRATION ORDER ===\n');
let changeCount = 0;
const renameCommands = [];

for (let i = 0; i < newOrder.length; i++) {
    const oldFile = newOrder[i];
    const mig = migrations[fileIndex[oldFile]];
    const needsMove = issues.some(is => is.referencedBy === oldFile) ||
        issues.some(is => is.migration === oldFile);

    // Extract the descriptive part after the timestamp
    const descMatch = oldFile.match(/^\d{4}_\d{2}_\d{2}_\d{6}(.+)$/);
    const desc = descMatch ? descMatch[1] : '_' + oldFile;

    const newTs = nextTimestamp();
    const newFile = newTs + desc;

    if (oldFile !== newFile) {
        console.log(`  RENAME: ${oldFile}`);
        console.log(`     TO:  ${newFile}  (${mig.tableName || 'alter'})`);
        renameCommands.push(`mv "${oldFile}" "${newFile}"`);
        changeCount++;
    } else {
        console.log(`  OK: ${oldFile}  (${mig.tableName || 'alter'})`);
    }
}

console.log(`\nFiles to rename: ${changeCount}`);

if (process.argv.includes('--rename')) {
    console.log('\nExecuting renames...');
    for (const cmd of renameCommands) {
        console.log(`  ${cmd}`);
        fs.renameSync(
            path.join(dir, cmd.split('"')[1]),
            path.join(dir, cmd.split('"')[3])
        );
    }
    console.log('Done!');
} else {
    console.log('\nRun with --rename flag to execute renames.');
    // Write rename script
    const scriptContent = '#!/bin/bash\ncd "' + dir + '"\n' + renameCommands.join('\n') + '\n';
    fs.writeFileSync(path.join(dir, '_rename.sh'), scriptContent);
    console.log('Rename script written to _rename.sh');
}