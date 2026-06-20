<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dokumen pendukung PIB (invoice, packing list, BL/AWB, COO, manifest).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('pib_supporting_docs')) {
            Schema::connection($this->connection)->create('pib_supporting_docs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pib_document_id')->constrained()->cascadeOnDelete();
                $table->enum('type', [
                    'invoice',
                    'packing_list',
                    'bl',
                    'awb',
                    'coo',
                    'manifest',
                    'other',
                ]);
                $table->string('file_path', 500);
                $table->string('original_name', 255)->nullable();
                $table->string('mime_type', 100)->nullable();
                $table->unsignedInteger('size_bytes')->nullable();
                $table->timestamps();

                $table->index(['pib_document_id', 'type']);
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('pib_supporting_docs');
    }
};