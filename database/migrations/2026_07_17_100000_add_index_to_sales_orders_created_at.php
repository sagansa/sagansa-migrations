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

        if (! $schema->hasTable('sales_orders')) {
            return;
        }

        // Index untuk query sales dashboard yang memfilter/mengurutkan
        // berdasarkan created_at.
        if (! $schema->hasIndex('sales_orders', 'sales_orders_created_at_index')) {
            $schema->table('sales_orders', function (Blueprint $table) {
                $table->index('created_at', 'sales_orders_created_at_index');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('sales_orders')) {
            return;
        }

        if ($schema->hasIndex('sales_orders', 'sales_orders_created_at_index')) {
            $schema->table('sales_orders', function (Blueprint $table) {
                $table->dropIndex('sales_orders_created_at_index');
            });
        }
    }
};
