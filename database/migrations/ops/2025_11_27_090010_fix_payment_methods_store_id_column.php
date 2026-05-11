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
        // Check current column type
        $columnType = DB::select("SHOW COLUMNS FROM payment_methods WHERE Field = 'store_id'")[0]->Type ?? '';
        
        // Only modify if not already CHAR(36) or UUID
        if (strpos($columnType, 'char(36)') === false && strpos($columnType, 'uuid') === false) {
            // Get all foreign keys for this table
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'payment_methods' 
                AND COLUMN_NAME = 'store_id' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ");
            
            // Drop existing foreign keys if any
            if (!empty($foreignKeys)) {
                foreach ($foreignKeys as $fk) {
                    DB::statement("ALTER TABLE payment_methods DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                }
            }
            
            // Modify column to CHAR(36) for UUID
            DB::statement('ALTER TABLE payment_methods MODIFY COLUMN store_id CHAR(36) NOT NULL');
            
            // Re-add foreign key constraint
            DB::statement('
                ALTER TABLE payment_methods 
                ADD CONSTRAINT payment_methods_store_id_foreign 
                FOREIGN KEY (store_id) 
                REFERENCES stores(id) 
                ON DELETE CASCADE
            ');
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
