<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'status')) {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled', 'saved') DEFAULT 'pending'");
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->enum('status', ['pending', 'completed', 'cancelled', 'saved'])->default('pending')->after('grand_total');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
