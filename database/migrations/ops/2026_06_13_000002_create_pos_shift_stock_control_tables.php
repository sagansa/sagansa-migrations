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

        if (! $schema->hasTable('pos_shift_sessions')) {
            $schema->create('pos_shift_sessions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('tenant_id')->nullable()->index();
                $table->uuid('store_id')->index();
                $table->uuid('opened_by_user_id')->nullable()->index();
                $table->uuid('closed_by_user_id')->nullable()->index();
                $table->timestamp('opened_at')->nullable()->index();
                $table->timestamp('closed_at')->nullable();
                $table->date('business_date')->index();
                $table->string('status', 32)->default('open')->index();
                $table->text('opening_note')->nullable();
                $table->text('closing_note')->nullable();
                $table->boolean('is_force_closed')->default(false);
                $table->uuid('force_closed_by_user_id')->nullable()->index();
                $table->text('force_close_reason')->nullable();
                $table->timestamps();

                $table->index(['store_id', 'status']);
                $table->index(['store_id', 'business_date']);
            });
        }

        if (! $schema->hasTable('pos_shift_stock_items')) {
            $schema->create('pos_shift_stock_items', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('shift_session_id')->index();
                $table->uuid('product_id')->index();
                $table->unsignedInteger('opening_stock')->default(0);
                $table->unsignedInteger('addition_stock')->default(0);
                $table->unsignedInteger('sold_quantity')->default(0);
                $table->integer('expected_closing_stock')->default(0);
                $table->unsignedInteger('actual_closing_stock')->nullable();
                $table->integer('variance')->nullable();
                $table->integer('opening_variance')->nullable();
                $table->text('opening_variance_note')->nullable();
                $table->text('closing_note')->nullable();
                $table->timestamps();

                $table->unique(['shift_session_id', 'product_id'], 'pos_shift_stock_items_unique_product');
            });
        }

        if (! $schema->hasTable('pos_shift_stock_movements')) {
            $schema->create('pos_shift_stock_movements', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('shift_session_id')->index();
                $table->uuid('product_id')->index();
                $table->uuid('order_id')->nullable()->index();
                $table->uuid('order_item_id')->nullable()->index();
                $table->string('type', 32)->index();
                $table->integer('quantity');
                $table->text('note')->nullable();
                $table->uuid('created_by_user_id')->nullable()->index();
                $table->timestamps();

                $table->index(['shift_session_id', 'type']);
            });
        }

        if (! $schema->hasTable('pos_shift_audit_logs')) {
            $schema->create('pos_shift_audit_logs', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('shift_session_id')->index();
                $table->string('action', 64)->index();
                $table->json('before_payload')->nullable();
                $table->json('after_payload')->nullable();
                $table->text('reason')->nullable();
                $table->uuid('created_by_user_id')->nullable()->index();
                $table->timestamps();
            });
        }

        if ($schema->hasTable('orders') && ! $schema->hasColumn('orders', 'shift_session_id')) {
            $schema->table('orders', function (Blueprint $table) {
                $table->uuid('shift_session_id')->nullable()->after('store_id')->index();
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if ($schema->hasTable('orders') && $schema->hasColumn('orders', 'shift_session_id')) {
            $schema->table('orders', function (Blueprint $table) {
                $table->dropColumn('shift_session_id');
            });
        }

        $schema->dropIfExists('pos_shift_audit_logs');
        $schema->dropIfExists('pos_shift_stock_movements');
        $schema->dropIfExists('pos_shift_stock_items');
        $schema->dropIfExists('pos_shift_sessions');
    }
};
