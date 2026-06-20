<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Toggle aktivasi channel notifikasi per event/urgency.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('notification_settings')) {
            Schema::connection($this->connection)->create('notification_settings', function (Blueprint $table) {
                $table->id();
                $table->string('channel', 16)->unique();        // fcm|telegram|email
                $table->boolean('is_enabled')->default(true);
                $table->boolean('notify_normal')->default(false); // HIJAU/SPPB (opsional)
                $table->boolean('notify_urgent')->default(true);  // NOTUL/MERAH/DENDA (wajib)
                $table->json('target_recipient')->nullable();     // chat_id, email list, topic
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('notification_settings');
    }
};