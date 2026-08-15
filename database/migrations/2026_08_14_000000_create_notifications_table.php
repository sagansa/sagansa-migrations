<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tabel notification center berada di DB sagansa (koneksi mysql).
    protected $connection = 'mysql';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        // Notification center mobile memakai tabel sendiri (notification_center)
        // agar tidak bentrok dengan tabel `notifications` standar Laravel yang
        // dipakai Filament admin (notifiable_type/notifiable_id). Migrasi
        // 2026_08_15 menangani DB yang sudah terlanjur memakai skema lama.
        if (! $schema->hasTable('notification_center')) {
            $schema->create('notification_center', function (Blueprint $table) {
                $table->id();
                // Loose FK ke sagansa_user.users (tanpa constraint enforced,
                // mengikuti pola device_tokens).
                $table->unsignedBigInteger('user_id')->index();
                $table->string('type'); // invoice_transfer_created | payment_receipt_paid
                $table->string('title');
                $table->text('body');
                $table->json('data')->nullable(); // invoice_id / receipt_id / payment_for
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'read_at']);
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);
        $schema->dropIfExists('notification_center');
    }
};
