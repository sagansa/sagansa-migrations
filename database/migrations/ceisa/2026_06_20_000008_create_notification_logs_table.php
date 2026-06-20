<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Log pengiriman notifikasi multi-channel.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('notification_logs')) {
            Schema::connection($this->connection)->create('notification_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pib_document_id')->nullable()->constrained()->nullOnDelete();
                $table->string('channel', 16);                 // fcm|telegram|email
                $table->string('event', 64)->nullable();       // notul|merah|denda|reminder...
                $table->string('recipient', 255)->nullable();
                $table->string('subject', 255)->nullable();
                $table->json('payload')->nullable();
                $table->string('status', 16)->default('queued'); // queued|sent|failed
                $table->string('error_message', 500)->nullable();
                $table->unsignedTinyInteger('attempts')->default(0);
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();

                $table->index(['channel', 'status']);
                $table->index('pib_document_id');
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('notification_logs');
    }
};