<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Item checklist per sesi pemeriksaan. Saat check disubmit, label-label dari
 * `asset_categories.checklist_definition` di-snapshot ke sini sebagai baris
 * terpisah beserta hasilnya (value 0=not_ok, 1=ok) dan catatan opsional.
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (!Schema::hasTable('asset_check_items')) {
            Schema::create('asset_check_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('asset_check_id')->index('asset_check_items_asset_check_id_foreign');
                $table->string('label');
                // 0=not_ok, 1=ok.
                $table->tinyInteger('value')->default(1);
                $table->text('note')->nullable();
                $table->timestamps();

                $table->foreign('asset_check_id', 'asset_check_items_asset_check_id_foreign')
                    ->references('id')->on('asset_checks')
                    ->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (Schema::hasTable('asset_check_items')) {
            Schema::table('asset_check_items', function (Blueprint $table) {
                $table->dropForeign('asset_check_items_asset_check_id_foreign');
            });
        }
        Schema::dropIfExists('asset_check_items');
    }
};
