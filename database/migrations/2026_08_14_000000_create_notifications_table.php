<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Tabel notifications berada di DB sagansa (koneksi mysql).
    protected $connection = 'mysql';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        // Tabel notifications sebelumnya memakai skema default Laravel
        // (notifiable_type / notifiable_id) yang tidak terpakai di project ini.
        // Ganti dengan skema notification center kita (user_id, type, dll).
        if ($schema->hasTable('notifications') && ! $schema->hasColumn('notifications', 'user_id')) {
            $schema->drop('notifications');
        }

        if (! $schema->hasTable('notifications')) {
            $schema->create('notifications', function (Blueprint $table) {
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
        $schema->dropIfExists('notifications');
    }
};
