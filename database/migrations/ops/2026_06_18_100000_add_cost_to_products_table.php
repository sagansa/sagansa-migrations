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

        if (! $schema->hasTable('products')) {
            return;
        }

        if (! $schema->hasColumn('products', 'cost')) {
            $schema->table('products', function (Blueprint $table) {
                $table->unsignedInteger('cost')->default(0)->after('price');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('products')) {
            return;
        }

        if ($schema->hasColumn('products', 'cost')) {
            $schema->table('products', function (Blueprint $table) {
                $table->dropColumn('cost');
            });
        }
    }
};