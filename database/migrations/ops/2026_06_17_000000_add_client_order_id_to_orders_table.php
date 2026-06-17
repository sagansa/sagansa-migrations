<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds `client_order_id` column to `orders` table to support offline order
 * queue idempotency (PRD: pos-offline-first-cache, Phase 7 Step 23).
 *
 * When a POS device creates an order while offline, it stores a locally
 * generated UUID (`client_order_id`) in the offline queue. During sync,
 * the server receives the `client_order_id` and must return the existing
 * order if the same id has already been processed — preventing duplicate
 * orders caused by retries.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        if ($schema->hasTable('orders') && ! $schema->hasColumn('orders', 'client_order_id')) {
            $schema->table('orders', function (Blueprint $table) {
                // Nullable + unique: only offline-synced orders carry this id.
                // Existing online orders remain null and are unaffected.
                $table->uuid('client_order_id')->nullable()->after('device_identifier');
                $table->index('client_order_id');
            });

            // Add a unique constraint only if there are no duplicate-null conflicts.
            // MySQL/MariaDB allows multiple NULLs in a UNIQUE index, so this is safe.
            try {
                $schema->table('orders', function (Blueprint $table) {
                    $table->unique('client_order_id', 'orders_client_order_id_unique');
                });
            } catch (\Throwable $e) {
                // If unique constraint cannot be added (e.g. duplicates), continue.
                // Idempotency will still work via firstOrCreate lookup.
            }
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if ($schema->hasTable('orders') && $schema->hasColumn('orders', 'client_order_id')) {
            try {
                $schema->table('orders', function (Blueprint $table) {
                    $table->dropUnique('orders_client_order_id_unique');
                });
            } catch (\Throwable $e) {
                // ignore if not exists
            }

            $schema->table('orders', function (Blueprint $table) {
                $table->dropIndex(['client_order_id']);
                $table->dropColumn('client_order_id');
            });
        }
    }
};