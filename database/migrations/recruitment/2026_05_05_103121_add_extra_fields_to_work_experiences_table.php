<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_recruitment';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('work_experiences', function (Blueprint $table) {
            $table->decimal('salary', 15, 2)->nullable()->after('position');
            $table->string('supervisor_name')->nullable()->after('salary');
            $table->string('supervisor_phone')->nullable()->after('supervisor_name');
            $table->boolean('is_contactable')->default(false)->after('supervisor_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_experiences', function (Blueprint $table) {
            $table->dropColumn(['salary', 'supervisor_name', 'supervisor_phone', 'is_contactable']);
        });
    }
};
