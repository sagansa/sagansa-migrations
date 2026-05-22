<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_recruitment';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('work_experiences')) {
            Schema::create('work_experiences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('company_name');
                $table->string('position');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
