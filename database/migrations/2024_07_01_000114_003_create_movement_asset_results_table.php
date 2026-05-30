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
        if (!Schema::hasTable('movement_asset_results')) {
            Schema::create('movement_asset_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id')->index('movement_asset_results_store_id_foreign');
            $table->date('date');
            $table->unsignedBigInteger('executor_id')->index('movement_asset_results_executor_id_foreign');
            $table->unsignedBigInteger('supervisor_id')->index('movement_asset_results_supervisor_id_foreign');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('movement_asset_results_user_id_foreign');
            $table->timestamps();
            $table->foreign(['executor_id'])->references(['id'])->on('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['store_id'])->references(['id'])->on('stores')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['supervisor_id'])->references(['id'])->on('employees')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movement_asset_results', function (Blueprint $table) {
            $table->dropForeign('movement_asset_results_executor_id_foreign');
            $table->dropForeign('movement_asset_results_store_id_foreign');
            $table->dropForeign('movement_asset_results_supervisor_id_foreign');
            
        });
        Schema::dropIfExists('movement_asset_results');
    }
};
