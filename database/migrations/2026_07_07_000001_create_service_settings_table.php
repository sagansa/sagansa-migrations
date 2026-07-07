<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        if (! $schema->hasTable('service_settings')) {
            $schema->create('service_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        $seeder = DB::connection($this->connection)->table('service_settings');

        if ($seeder->where('key', 'force_mobile_only')->doesntExist()) {
            $seeder->insert([
                'key' => 'force_mobile_only',
                'value' => '1',
                'description' => 'Saat aktif, hanya admin & super-admin yang bisa akses panel admin. Nonaktifkan untuk darurat.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);
        $schema->dropIfExists('service_settings');
    }
};
