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
        if (!Schema::hasTable('permit_employees')) {
            Schema::create('permit_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('reason');
            $table->date('from_date');
            $table->date('until_date');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('permit_employees_created_by_id_foreign');
            $table->unsignedBigInteger('approved_by_id')->nullable()->index('permit_employees_approved_by_id_foreign');
            $table->timestamps();
    });
        }
    }/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permit_employees', function (Blueprint $table) {
            $table->dropForeign('permit_employees_approved_by_id_foreign');
            $table->dropForeign('permit_employees_created_by_id_foreign');
        });
        Schema::dropIfExists('permit_employees');
    }
};
