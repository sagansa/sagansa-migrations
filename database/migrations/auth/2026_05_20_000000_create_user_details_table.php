<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        if (!Schema::hasTable('user_details')) {
                    Schema::create('user_details', function (Blueprint $table) {
                        $table->uuid('id')->primary(); // matches user uuid
                        $table->uuid('tenant_id')->nullable()->index();
                        $table->string('role')->default('staff');
                        $table->boolean('is_active')->default(true);
                        $table->uuid('manager_id')->nullable();
                        $table->string('invitation_token')->nullable();
                        $table->timestamp('invitation_token_expires_at')->nullable();
                        $table->timestamp('invited_at')->nullable();
                        $table->uuid('invited_by')->nullable();
                        $table->string('verification_token')->nullable();
                        $table->timestamps();
                    });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
