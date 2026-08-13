<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        Schema::table('hygiene_of_rooms', function (Blueprint $table) {
            $table->tinyInteger('condition')
                ->nullable()
                ->after('image')
                ->comment('1=Bersih, 2=Perlu Perhatian, 3=Kotor, 4=Tidak Sesuai');
            $table->text('notes')
                ->nullable()
                ->after('condition');
        });
    }

    public function down(): void
    {
        Schema::table('hygiene_of_rooms', function (Blueprint $table) {
            $table->dropColumn(['condition', 'notes']);
        });
    }
};
