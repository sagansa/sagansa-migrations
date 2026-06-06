<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_recruitment';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('work_experiences')) {
            return;
        }

        $columns = [
            'salary' => fn (Blueprint $table) => $table->decimal('salary', 15, 2)->nullable()->after('position'),
            'supervisor_name' => fn (Blueprint $table) => $table->string('supervisor_name')->nullable()->after('salary'),
            'supervisor_phone' => fn (Blueprint $table) => $table->string('supervisor_phone')->nullable()->after('supervisor_name'),
            'is_contactable' => fn (Blueprint $table) => $table->boolean('is_contactable')->default(false)->after('supervisor_phone'),
        ];

        foreach ($columns as $column => $definition) {
            if (Schema::hasColumn('work_experiences', $column)) {
                continue;
            }

            Schema::table('work_experiences', function (Blueprint $table) use ($definition) {
                $definition($table);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('work_experiences')) {
            return;
        }

        $columns = array_filter([
            'salary',
            'supervisor_name',
            'supervisor_phone',
            'is_contactable',
        ], fn (string $column) => Schema::hasColumn('work_experiences', $column));

        if ($columns === []) {
            return;
        }

        Schema::table('work_experiences', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};
