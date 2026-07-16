<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('product_prices')) {
            return;
        }

        $hasVariantColumn = Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id');

        if ($hasVariantColumn) {
            DB::connection($this->connection)
                ->table('product_prices')
                ->whereNotNull('variant_id')
                ->delete();
        }

        $rows = DB::connection($this->connection)
            ->table('product_prices')
            ->orderByDesc('updated_at')
            ->get();

        $seen = [];
        foreach ($rows as $row) {
            $key = "{$row->store_id}:{$row->product_id}:{$row->customer_type_id}";
            if (isset($seen[$key])) {
                DB::connection($this->connection)->table('product_prices')->where('id', $row->id)->delete();
                continue;
            }
            $seen[$key] = true;
        }

        Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
            if (Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id')) {
                try {
                    $table->dropForeign(['variant_id']);
                } catch (Throwable) {
                    // FK may not exist.
                }
            }

            try {
                $table->dropUnique('product_prices_scope_unique');
            } catch (Throwable) {
                // Index may not exist.
            }
        });

        if (Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id')) {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }

        Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
            try {
                $table->unique(
                    ['store_id', 'product_id', 'customer_type_id'],
                    'product_prices_store_product_type_unique'
                );
            } catch (Throwable) {
                // Unique may already exist.
            }
        });
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('product_prices')) {
            return;
        }

        if (! Schema::connection($this->connection)->hasColumn('product_prices', 'variant_id')) {
            Schema::connection($this->connection)->table('product_prices', function (Blueprint $table) {
                $table->uuid('variant_id')->nullable()->after('product_id');
                $table->index('variant_id');
            });
        }
    }
};
