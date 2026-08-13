<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('user_stores')) {
            Schema::connection($this->connection)->create('user_stores', function (Blueprint $table) {
                $table->uuid('user_id');
                $table->uuid('store_id');
                $table->timestamps();

                // user_id points to sagansa_user.users.uuid, which lives outside sagansa_ops.
                $table->index('user_id');
                $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

                // Composite primary key
                $table->primary(['user_id', 'store_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('user_stores');
    }
};
