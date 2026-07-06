<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Temuan/tindak lanjut dari pemeriksaan. Issue dapat terbentuk otomatis saat
 * sebuah `asset_checks` memiliki severity >= ringan, atau dibuat manual.
 *
 * Modul issue bersifat sederhana (status open/closed) — tidak ada modul
 * repair tracking terpisah.
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (!Schema::hasTable('asset_issues')) {
            Schema::create('asset_issues', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('asset_id')->index('asset_issues_asset_id_foreign');
                $table->unsignedBigInteger('asset_check_id')->nullable()->index('asset_issues_asset_check_id_foreign');

                // 2=ringan, 3=sedang, 4=berat (sama skala severity dgn asset_checks).
                $table->tinyInteger('severity')->default(2);
                $table->text('description')->nullable();

                // 1=open, 2=closed.
                $table->tinyInteger('status')->default(1);

                // Audit (loose ke users cross-DB).
                $table->unsignedBigInteger('reported_by_id')->nullable()->index('asset_issues_reported_by_id_index');
                $table->unsignedBigInteger('resolved_by_id')->nullable()->index('asset_issues_resolved_by_id_index');
                $table->timestamp('resolved_at')->nullable();

                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['asset_id', 'status'], 'asset_issues_asset_status_index');
                $table->index('status', 'asset_issues_status_index');

                $table->foreign('asset_id', 'asset_issues_asset_id_foreign')
                    ->references('id')->on('assets')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('asset_check_id', 'asset_issues_asset_check_id_foreign')
                    ->references('id')->on('asset_checks')
                    ->onUpdate('cascade')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('asset_issues')) {
            Schema::table('asset_issues', function (Blueprint $table) {
                $table->dropForeign('asset_issues_asset_id_foreign');
                $table->dropForeign('asset_issues_asset_check_id_foreign');
            });
        }
        Schema::dropIfExists('asset_issues');
    }
};
