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
        if (!Schema::hasTable('savings')) {
            Schema::create('savings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('debet_credit');
            $table->unsignedBigInteger('employee_id')->nullable()->index('savings_employee_id_foreign');
            $table->bigInteger('nominal');
            $table->timestamps();
            $table->foreign(['employee_id'])->references(['id'])->on('employees')->onUpdate('cascade')->onDelete('cascade');
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            $table->dropForeign('savings_employee_id_foreign');
        });
        Schema::dropIfExists('savings');
    }
};
