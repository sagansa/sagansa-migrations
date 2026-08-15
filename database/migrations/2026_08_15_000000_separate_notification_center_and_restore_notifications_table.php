<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Eksekusi di DB sagansa (koneksi mysql).
    protected $connection = 'mysql';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        // Dulu tabel `notifications` standar Laravel dijatuhkan dan diganti
        // skema notification center (user_id, title, body) — padahal tabel
        // `notifications` standar dipakai Filament admin (notifiable_type /
        // notifiable_id) untuk database notifications. Pisahkan keduanya:
        // 1. DB terlanjur (tabel notifications ber-skema custom, ada kolom
        //    user_id) -> rename jadi notification_center, data ikut terjaga.
        // 2. Buat ulang tabel notifications standar Laravel untuk admin.
        $hasCustomNotifications = $schema->hasTable('notifications')
            && $schema->hasColumn('notifications', 'user_id');

        if ($hasCustomNotifications) {
            if (! $schema->hasTable('notification_center')) {
                $schema->rename('notifications', 'notification_center');
            } else {
                // notification_center sudah ada dari migrasi 2026_08_14 yang
                // baru; tabel custom lama tidak terpakai lagi.
                $schema->drop('notifications');
            }
        }

        if (! $schema->hasTable('notification_center')) {
            $schema->create('notification_center', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('type');
                $table->string('title');
                $table->text('body');
                $table->json('data')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'read_at']);
                $table->index(['user_id', 'created_at']);
            });
        }

        // Skema standar Laravel yang dibaca Filament (morphMany notifiable).
        // created_at wajib: Filament mengurutkan list notifikasi dengannya.
        if (! $schema->hasTable('notifications')) {
            $schema->create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        } else {
            if (! $schema->hasColumn('notifications', 'created_at')) {
                $schema->table('notifications', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable()->after('read_at');
                });
            }
            if (! $schema->hasColumn('notifications', 'updated_at')) {
                $schema->table('notifications', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable()->after('created_at');
                });
            }
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        // Kembalikan nama tabel notification_center menjadi notifications
        // (skema lama) dan jatuhkan tabel standar — hanya bila kondisi aman.
        $schema->dropIfExists('notifications');

        if ($schema->hasTable('notification_center')) {
            $schema->rename('notification_center', 'notifications');
        }
    }
};
