<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->boolean('is_active')
                ->default(true)
                ->after('name')
                ->comment('1=Aktif (tampil di form kebersihan), 0=Non-aktif');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
