<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('orders')) {
            return;
        }

        if (Schema::connection($this->connection)->hasColumn('orders', 'payment_method')) {
            return;
        }

        Schema::connection($this->connection)->table('orders', function (Blueprint $table) {
            if (Schema::connection('mysql_ops')->hasColumn('orders', 'payment_type_id')) {
                $table->string('payment_method')->nullable()->after('payment_type_id');
            } else {
                $table->string('payment_method')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('orders')) {
            return;
        }

        if (! Schema::connection($this->connection)->hasColumn('orders', 'payment_method')) {
            return;
        }

        Schema::connection($this->connection)->table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
