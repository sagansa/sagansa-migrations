<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Detil barang PIB (items).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('pib_items')) {
            Schema::connection($this->connection)->create('pib_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pib_document_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('seri')->default(1);
                $table->string('hs_code', 32)->nullable();        // BTKI
                $table->string('uraian_barang', 500)->nullable();
                $table->string('negara_asal', 4)->nullable();
                $table->decimal('jumlah_satuan', 18, 4)->default(0);
                $table->string('satuan', 16)->nullable();
                $table->decimal('nilai_cif', 18, 2)->default(0);
                $table->decimal('bea_masuk', 18, 2)->default(0);
                $table->decimal('ppn', 18, 2)->default(0);
                $table->decimal('pph', 18, 2)->default(0);
                $table->timestamps();

                $table->index('pib_document_id');
                $table->index('hs_code');
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('pib_items');
    }
};