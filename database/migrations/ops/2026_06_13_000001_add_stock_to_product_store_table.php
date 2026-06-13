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

        if (! $schema->hasTable('product_store')) {
            return;
        }

        $schema->table('product_store', function (Blueprint $table) use ($schema) {
            if (! $schema->hasColumn('product_store', 'stock')) {
                $table->unsignedInteger('stock')->nullable()->after('price');
            }
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('product_store')) {
            return;
        }

        $schema->table('product_store', function (Blueprint $table) use ($schema) {
            if ($schema->hasColumn('product_store', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
};
