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
        Schema::connection($this->connection)->table('orders', function (Blueprint $table) {
            if (! Schema::connection($this->connection)->hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
            }

            if (! Schema::connection($this->connection)->hasColumn('orders', 'total_refunded')) {
                $table->decimal('total_refunded', 15, 2)->default(0)->after('grand_total');
            }

            if (! Schema::connection($this->connection)->hasColumn('orders', 'refund_count')) {
                $table->integer('refund_count')->default(0)->after('total_refunded');
            }
        });

        Schema::connection($this->connection)->table('order_items', function (Blueprint $table) {
            if (! Schema::connection($this->connection)->hasColumn('order_items', 'quantity_refunded')) {
                $table->integer('quantity_refunded')->default(0)->after('quantity');
            }

            if (! Schema::connection($this->connection)->hasColumn('order_items', 'refund_amount')) {
                $table->decimal('refund_amount', 15, 2)->default(0)->after('total_price');
            }
        });

        if (! Schema::connection($this->connection)->hasTable('refunds')) {
            Schema::connection($this->connection)->create('refunds', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('tenant_id')->index();
                $table->uuid('order_id')->index();
                $table->string('refund_number', 50)->unique();
                $table->enum('refund_type', ['full', 'partial']);
                $table->decimal('total_amount', 15, 2);
                $table->text('reason')->nullable();
                $table->text('notes')->nullable();
                $table->uuid('refunded_by')->index();
                $table->timestamp('refunded_at');
                $table->string('payment_method', 50)->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('completed')->index();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            });
        }

        if (! Schema::connection($this->connection)->hasTable('refund_items')) {
            Schema::connection($this->connection)->create('refund_items', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('refund_id')->index();
                $table->uuid('order_item_id')->index();
                $table->integer('quantity_refunded');
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_refund_amount', 15, 2);
                $table->text('reason')->nullable();
                $table->timestamps();

                $table->foreign('refund_id')->references('id')->on('refunds')->cascadeOnDelete();
                $table->foreign('order_item_id')->references('id')->on('order_items')->restrictOnDelete();
            });
        }

        if (Schema::connection($this->connection)->hasTable('order_payments')) {
            Schema::connection($this->connection)->table('order_payments', function (Blueprint $table) {
                if (! Schema::connection($this->connection)->hasColumn('order_payments', 'status')) {
                    $table->string('status')->default('pending')->after('amount');
                }

                if (! Schema::connection($this->connection)->hasColumn('order_payments', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('payment_type_id');
                }

                if (! Schema::connection($this->connection)->hasColumn('order_payments', 'paid_at')) {
                    $table->timestamp('paid_at')->nullable()->after('captured_at');
                }
            });

            if (Schema::connection($this->connection)->hasColumn('order_payments', 'payment_type_id')) {
                try {
                    DB::connection($this->connection)->statement('ALTER TABLE order_payments MODIFY payment_type_id CHAR(36) NULL');
                } catch (Throwable $e) {
                    // Some database engines cannot alter this column safely; runtime code skips creating payments when it cannot.
                }
            }
        }
    }

    public function down(): void
    {
        // Intentionally left non-destructive. These columns/tables are part of POS order accounting.
    }
};
