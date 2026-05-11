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
        // Check current column type for id
        $columnType = DB::select("SHOW COLUMNS FROM payment_methods WHERE Field = 'id'")[0]->Type ?? '';
        
        // Only modify if not already CHAR(36) or UUID
        if (strpos($columnType, 'char(36)') === false && strpos($columnType, 'uuid') === false) {
            // First, remove AUTO_INCREMENT if exists
            DB::statement('ALTER TABLE payment_methods MODIFY COLUMN id BIGINT UNSIGNED NOT NULL');
            
            // Then drop primary key
            DB::statement('ALTER TABLE payment_methods DROP PRIMARY KEY');
            
            // Modify id column to CHAR(36) for UUID
            DB::statement('ALTER TABLE payment_methods MODIFY COLUMN id CHAR(36) NOT NULL');
            
            // Re-add primary key
            DB::statement('ALTER TABLE payment_methods ADD PRIMARY KEY (id)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible - would lose data
    }
};
