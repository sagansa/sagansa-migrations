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
        // berdasarkan delivery_date.
        if (! $schema->hasIndex('sales_orders', 'sales_orders_delivery_date_index')) {
            $schema->table('sales_orders', function (Blueprint $table) {
                $table->index('delivery_date', 'sales_orders_delivery_date_index');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('sales_orders')) {
            return;
        }

        if ($schema->hasIndex('sales_orders', 'sales_orders_delivery_date_index')) {
            $schema->table('sales_orders', function (Blueprint $table) {
                $table->dropIndex('sales_orders_delivery_date_index');
            });
        }
    }
};
