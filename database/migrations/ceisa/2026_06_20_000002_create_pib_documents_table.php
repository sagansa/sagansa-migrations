<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * BC 2.0 — Pemberitahuan Impor Barang (PIB).
 * Tabel utama header PIB + tracking status + flag underprice.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('pib_documents')) {
            Schema::connection($this->connection)->create('pib_documents', function (Blueprint $table) {
                $table->id();
                $table->string('aju_number', 64)->unique()->nullable();
                $table->string('registration_number', 64)->nullable();
                $table->string('kantor_pabean', 16)->nullable();
                $table->string('importir_npwp', 32)->nullable();
                $table->string('importir_name', 255)->nullable();
                $table->string('ppjk_npwp', 32)->nullable();
                $table->string('jenis_transaksi', 16)->nullable();
                $table->string('sarana_angkut', 64)->nullable();
                $table->string('pelabuhan_muat', 16)->nullable();
                $table->string('pelabuhan_bongkar', 16)->nullable();
                $table->string('status', 32)->default('draft'); // draft|aju|hijau|merah|sppb|notul|denda
                $table->decimal('valuation_declaration', 18, 2)->default(0);
                $table->decimal('valuation_final', 18, 2)->nullable();
                $table->boolean('is_underprice')->default(false);
                $table->date('due_date_ssp')->nullable();
                // Reference / Response ID dari CEISA saat submit
                $table->string('ceisa_response_id', 128)->nullable();
                $table->string('ceisa_reference', 128)->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('last_webhook_at')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('is_underprice');
                $table->index('importir_npwp');
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('pib_documents');
    }
};