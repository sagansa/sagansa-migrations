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
        Schema::create('offline_sync_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('device_identifier');
            $table->string('payload_type'); // e.g., 'order', 'attendance', 'printer_job'
            $table->string('payload_id'); // ID of the actual record
            $table->enum('status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamp('synced_at')->nullable();
            $table->text('error_details')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_sync_logs');
    }
};
