<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Eksekusi di DB sagansa (koneksi mysql).
    protected $connection = 'mysql';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('notifications')) {
            return;
        }

        // Filament mengurutkan & paginate database notifications dengan
        // created_at, sedangkan skema notifications standar Laravel tidak
        // memilikinya — tambahkan sebagai kolom nullable.
        if (! $schema->hasColumn('notifications', 'created_at')) {
            $schema->table('notifications', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable()->after('read_at');
            });
        }

        if (! $schema->hasColumn('notifications', 'updated_at')) {
            $schema->table('notifications', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('notifications')) {
            return;
        }

        foreach (['created_at', 'updated_at'] as $column) {
            if ($schema->hasColumn('notifications', $column)) {
                $schema->table('notifications', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
