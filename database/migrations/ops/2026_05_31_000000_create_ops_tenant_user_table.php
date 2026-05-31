<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('tenant_user')) {
            return;
        }

        Schema::connection($this->connection)->create('tenant_user', function (Blueprint $table) {
            $table->uuid('tenant_id');
            $table->uuid('user_id');
            $table->string('role')->default('support');
            $table->uuid('assigned_by')->nullable();
            $table->timestamps();

            $table->primary(['tenant_id', 'user_id']);
            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('tenant_user');
    }
};
