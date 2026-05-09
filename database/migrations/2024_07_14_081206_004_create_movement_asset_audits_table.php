<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('movement_asset_audits')) {
            Schema::create('movement_asset_audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movement_asset_id')->index('movement_asset_audits_movement_asset_id_foreign');
            $table->integer('good_cond_qty');
            $table->integer('bad_cond_qty');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('movement_asset_result_id')->index('movement_asset_audits_movement_asset_result_id_foreign');
            $table->timestamps();
            $table->foreign(['movement_asset_id'])->references(['id'])->on('movement_assets')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['movement_asset_result_id'])->references(['id'])->on('movement_asset_results')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movement_asset_audits', function (Blueprint $table) {
            $table->dropForeign('movement_asset_audits_movement_asset_id_foreign');
            $table->dropForeign('movement_asset_audits_movement_asset_result_id_foreign');
        });
        Schema::dropIfExists('movement_asset_audits');
    }
};
