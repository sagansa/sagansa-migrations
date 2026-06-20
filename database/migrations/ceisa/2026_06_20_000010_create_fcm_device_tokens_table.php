<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FCM Device Tokens dari aplikasi React Native (mobiles/ceisa).
 *
 * Catatan: user_id merujuk ke database `sagansa_user.users` (cross-database),
 * sehingga TIDAK menggunakan foreign key constraint agar tidak mengubah
 * struktur table di sagansa_user.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('fcm_device_tokens')) {
            Schema::connection($this->connection)->create('fcm_device_tokens', function (Blueprint $table) {
                $table->id();
                // Referensi ke sagansa_user.users (UUID/BIGINT — di sini string untuk fleksibilitas)
                $table->string('user_id', 64)->index();
                $table->string('device_token', 500);
                $table->string('platform', 16)->default('android'); // android|ios|web
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'is_active']);
                $table->index('device_token', 191);
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('fcm_device_tokens');
    }
};