<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menyimpan titik lokasi pegawai yang dikirim oleh aplikasi mobile:
 *  - workmanager periodic task (~tiap 2 jam, source=periodic)
 *  - jawaban FCM atas permintaan on-demand dari admin (source=on_demand)
 *
 * Catatan: `created_by_id` adalah loose reference ke tabel `users` di database
 * `sagansa_user` (cross-DB) — tidak dipasang FK constraint native sesuai
 * aturan design.md (struktur sagansa_user.users tidak boleh diubah).
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('employee_locations')) {
            Schema::create('employee_locations', function (Blueprint $table) {
                $table->bigIncrements('id');

                // Loose reference ke sagansa_user.users (cross-DB, tanpa FK).
                $table->unsignedBigInteger('created_by_id');

                $table->double('latitude');   // -90..90
                $table->double('longitude');  // -180..180
                $table->float('accuracy')->nullable();     // meter
                $table->string('source', 16);              // 'periodic' | 'on_demand'

                // UUID opsional — berkorelasi dengan location_requests.request_id
                // (loose reference, tabel location_requests belum punya FK native).
                $table->uuid('request_id')->nullable();

                $table->datetime('captured_at')->nullable();
                $table->timestamps();

                // Pola query utama: filter by user + sort by waktu terbaru,
                // subquery group by created_by_id (AdminTrackLocationController).
                $table->index(['created_by_id', 'captured_at']);
                $table->index('request_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_locations');
    }
};
