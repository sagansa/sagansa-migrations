<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur 1 — Manajemen Kredensial & Autentikasi.
 * Penyimpanan kredensial CEISA (Application ID, API Key) terenkripsi.
 *
 * Catatan: enkripsi di-handle via Laravel encrypted cast di model, bukan di DB.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('ceisa_credentials')) {
            Schema::connection($this->connection)->create('ceisa_credentials', function (Blueprint $table) {
                $table->id();
                $table->text('application_id');              // disimpan encrypted (cast) — TEXT karena ciphertext panjang
                $table->text('api_key');                     // disimpan encrypted (cast) — TEXT karena ciphertext panjang
                $table->enum('gateway_mode', ['sandbox', 'production'])->default('sandbox');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('ceisa_credentials');
    }
};