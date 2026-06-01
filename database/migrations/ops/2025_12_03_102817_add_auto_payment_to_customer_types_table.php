<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('customer_types')) {
            return;
        }

        Schema::connection($this->connection)->table('customer_types', function (Blueprint $table) {
            if (! Schema::connection('mysql_ops')->hasColumn('customer_types', 'auto_payment')) {
                $table->boolean('auto_payment')->default(false)->after('order');
            }

            if (! Schema::connection('mysql_ops')->hasColumn('customer_types', 'linked_payment_method_id')) {
                $table->uuid('linked_payment_method_id')->nullable()->after('auto_payment');
            }
        });

        if (! Schema::connection($this->connection)->hasTable('payment_methods')) {
            return;
        }

        try {
            Schema::connection($this->connection)->table('customer_types', function (Blueprint $table) {
                $table->foreign('linked_payment_method_id')
                    ->references('id')
                    ->on('payment_methods')
                    ->nullOnDelete();
            });
        } catch (Throwable) {
            // The column is still usable when the FK already exists or the host rejects FK creation.
        }
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('customer_types')) {
            return;
        }

        Schema::connection($this->connection)->table('customer_types', function (Blueprint $table) {
            if (Schema::connection('mysql_ops')->hasColumn('customer_types', 'linked_payment_method_id')) {
                try {
                    $table->dropForeign(['linked_payment_method_id']);
                } catch (Throwable) {
                    // FK may not exist on hosts where creation was skipped.
                }
            }
        });

        Schema::connection($this->connection)->table('customer_types', function (Blueprint $table) {
            if (Schema::connection('mysql_ops')->hasColumn('customer_types', 'linked_payment_method_id')) {
                $table->dropColumn('linked_payment_method_id');
            }

            if (Schema::connection('mysql_ops')->hasColumn('customer_types', 'auto_payment')) {
                $table->dropColumn('auto_payment');
            }
        });
    }
};
