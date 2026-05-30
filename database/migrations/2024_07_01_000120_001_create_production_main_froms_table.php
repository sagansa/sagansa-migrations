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
        if (!Schema::hasTable('production_main_froms')) {
            Schema::create('production_main_froms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('production_id')->index('production_main_froms_production_id_foreign');
            $table->unsignedBigInteger('detail_invoice_id')->index('production_main_froms_detail_invoice_id_foreign');
            $table->timestamps();
            $table->foreign(['detail_invoice_id'])->references(['id'])->on('detail_invoices')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['production_id'])->references(['id'])->on('productions')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_main_froms', function (Blueprint $table) {
            $table->dropForeign('production_main_froms_detail_invoice_id_foreign');
            $table->dropForeign('production_main_froms_production_id_foreign');
        });
        Schema::dropIfExists('production_main_froms');
    }
};
