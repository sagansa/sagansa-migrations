<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur 3a — NOTUL/SPTNP & Underprice.
 * Surat penetapan tarif/nilai pabean dari Bea Cukai.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('ceisa_notul_documents')) {
            Schema::connection($this->connection)->create('ceisa_notul_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pib_document_id')->constrained()->cascadeOnDelete();
                $table->string('nomor_surat', 128)->nullable();
                $table->date('tanggal_surat')->nullable();
                $table->string('hs_code', 32)->nullable();
                $table->string('uraian_barang', 500)->nullable();

                // Penetapan ulang nilai pabean (underprice detection)
                $table->decimal('nilai_deklarasi', 18, 2)->default(0);
                $table->decimal('nilai_penetapan_bc', 18, 2)->default(0);
                $table->decimal('selisih_bea_masuk', 18, 2)->default(0);
                $table->decimal('denda', 18, 2)->default(0);
                $table->decimal('ppn_pph', 18, 2)->default(0);
                $table->decimal('total_kewajiban', 18, 2)->default(0);

                $table->boolean('is_underprice')->default(false);
                $table->string('jenis_surat', 32)->default('NOTUL'); // NOTUL|SPTNP|SPP
                $table->date('due_date_ssp')->nullable();
                $table->string('rekening_ssp', 128)->nullable();

                $table->string('file_path', 500)->nullable();      // PDF NOTUL
                $table->json('raw_payload')->nullable();
                $table->timestamps();

                $table->index('pib_document_id');
                $table->index('is_underprice');
                $table->index('due_date_ssp');
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('ceisa_notul_documents');
    }
};