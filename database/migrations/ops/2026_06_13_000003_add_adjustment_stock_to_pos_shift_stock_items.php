<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        if (
            $schema->hasTable('pos_shift_stock_items')
            && ! $schema->hasColumn('pos_shift_stock_items', 'adjustment_stock')
        ) {
            $schema->table('pos_shift_stock_items', function (Blueprint $table) {
                $table->integer('adjustment_stock')->default(0)->after('addition_stock');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (
            $schema->hasTable('pos_shift_stock_items')
            && $schema->hasColumn('pos_shift_stock_items', 'adjustment_stock')
        ) {
            $schema->table('pos_shift_stock_items', function (Blueprint $table) {
                $table->dropColumn('adjustment_stock');
            });
        }
    }
};
