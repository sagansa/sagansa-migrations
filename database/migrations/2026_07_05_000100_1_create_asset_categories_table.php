<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tabel lookup kategori aset. Tiap kategori membawa frekuensi pemeriksaan
 * (dalam hari) dan definisi checklist (JSON) yang dipakai oleh form check.
 *
 * Contoh baris:
 *   - name="IT",           frequency_days=30,  checklist=[{"label":"Fisik"}, {"label":"Fungsi"}]
 *   - name="Kendaraan",    frequency_days=30,  checklist=[{"label":"Mesin"}, {"label":"Ban"}, {"label":"Body"}]
 *   - name="AC",           frequency_days=90,  checklist=[{"label":"Filter"}, {"label":"Freon"}]
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        if (!Schema::hasTable('asset_categories')) {
            Schema::create('asset_categories', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('description')->nullable();
                // Frekuensi pemeriksaan default untuk kategori ini (hari).
                $table->unsignedSmallInteger('frequency_days')->default(30);
                // Definisi checklist baku: array of {label, type}. Dipakai UI
                // untuk merender item checklist saat check aset dilakukan.
                $table->json('checklist_definition')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");
        Schema::dropIfExists('asset_categories');
    }
};
