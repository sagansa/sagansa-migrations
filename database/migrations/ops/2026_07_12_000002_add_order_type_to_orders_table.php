<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah orders.order_type — klasifikasi transaksi:
 *   sale     = penjualan normal (dihitung sebagai omzet bila grand_total > 0)
 *   comp     = gratis total / hadiah (bukan omzet)
 *   void     = dibatalkan (bukan omzet)
 *   training = order dummy pelatihan staf (bukan omzet)
 *
 * Default 'sale' agar baris existing (yang tidak punya konteks) tetap konservatif
 * (dianggap penjualan). Perhitungan omzet tetap difilter grand_total > 0.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('orders')) {
            return;
        }

        if (! $schema->hasColumn('orders', 'order_type')) {
            // SQLite (test) tidak dukung MODIFY ENUM; MySQL dukung.
            if (DB::connection($this->connection)->getDriverName() !== 'sqlite') {
                $schema->table('orders', function (Blueprint $table) {
                    $table->enum('order_type', ['sale', 'comp', 'void', 'training'])
                        ->default('sale')->after('status');
                });
            } else {
                $schema->table('orders', function (Blueprint $table) {
                    $table->string('order_type', 20)->default('sale')->after('status');
                });
            }
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('orders')) {
            return;
        }

        if ($schema->hasColumn('orders', 'order_type')) {
            $schema->table('orders', function (Blueprint $table) {
                $table->dropColumn('order_type');
            });
        }
    }
};
