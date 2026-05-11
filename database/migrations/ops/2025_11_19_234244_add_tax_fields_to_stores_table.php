<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('stores', 'tax_name')) {
                $table->string('tax_name')->default('Pajak')->after('tax_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['tax_rate', 'tax_name']);
        });
    }
};
