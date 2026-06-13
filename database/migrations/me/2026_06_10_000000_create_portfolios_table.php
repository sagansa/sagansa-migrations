<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_me';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('portfolios')) {
            Schema::connection($this->connection)->create('portfolios', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->string('thumbnail', 500)->nullable();
                $table->json('images')->nullable();
                $table->string('category', 255)->nullable();
                $table->string('client', 500)->nullable();
                $table->date('project_date')->nullable();
                $table->string('project_url', 500)->nullable();
                $table->json('technologies')->nullable();
                $table->boolean('is_published')->default(true);
                $table->integer('sort_order')->default(0);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();

                $table->index('is_published');
                $table->index('category');
                $table->index('sort_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('portfolios');
    }
};