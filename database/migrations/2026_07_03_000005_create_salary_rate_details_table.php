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
        if (!Schema::hasTable('salary_rate_details')) {
            Schema::create('salary_rate_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('salary_rate_id')->index();
                $table->integer('years_of_service');
                $table->decimal('rate_per_hour', 15, 2);
                $table->timestamps();

                $table->foreign('salary_rate_id')
                    ->references('id')
                    ->on('salary_rates')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('salary_rate_details')) {
            Schema::table('salary_rate_details', function (Blueprint $table) {
                $table->dropForeign(['salary_rate_id']);
            });
        }
        Schema::dropIfExists('salary_rate_details');
    }
};
