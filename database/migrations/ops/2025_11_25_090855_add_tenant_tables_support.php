<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->table('tables', function (Blueprint $table) {
            // Add tenant_id for food court mode
            if (!Schema::connection($this->connection)->hasColumn('tables', 'tenant_id')) {
                            $table->uuid('tenant_id')->nullable()->after('id')->index();            }
            
            // Add zone support
            if (!Schema::connection($this->connection)->hasColumn('tables', 'zone_id')) {
                            $table->uuid('zone_id')->nullable()->after('tenant_id')->index();            }
            
            // Add QR code
            if (!Schema::connection($this->connection)->hasColumn('tables', 'qr_code')) {
                            $table->string('qr_code', 255)->nullable()->unique()->after('table_number');            }
            
            // Make store_id nullable (for tenant-owned tables)  
            if (Schema::connection($this->connection)->hasColumn('tables', 'store_id')) {
                            $table->uuid('store_id')->nullable()->change();            }
        });


        if (DB::connection($this->connection)->getDriverName() !== 'sqlite') {
            // Generate QR codes for existing tables
            DB::connection($this->connection)->statement("
                UPDATE tables 
                SET qr_code = CONCAT('TBL-', UPPER(REPLACE(id, '-', '')))
                WHERE qr_code IS NULL
            ");

            // Add constraint: must have either store_id OR tenant_id (not both, not neither)
            if (!$this->constraintExists('tables', 'chk_table_owner')) {
                DB::connection($this->connection)->statement('
                    ALTER TABLE tables ADD CONSTRAINT chk_table_owner CHECK (
                        (store_id IS NOT NULL AND tenant_id IS NULL) OR
                        (store_id IS NULL AND tenant_id IS NOT NULL)
                    )
                ');
            }
        }
    }

    public function down(): void
    {
        // Drop constraint first
        if ($this->constraintExists('tables', 'chk_table_owner')) {
            DB::connection($this->connection)->statement('ALTER TABLE tables DROP CONSTRAINT chk_table_owner');
        }
        
        Schema::connection($this->connection)->table('tables', function (Blueprint $table) {
            $table->dropColumn('tenant_id');
            if (Schema::connection($this->connection)->hasColumn('tables', 'zone_id')) {
                            $table->dropColumn('zone_id');            }
            if (Schema::connection($this->connection)->hasColumn('tables', 'qr_code')) {
                            $table->dropColumn('qr_code');            }
            
            // Restore store_id as NOT NULL
            if (Schema::connection($this->connection)->hasColumn('tables', 'store_id')) {
                            $table->uuid('store_id')->nullable(false)->change();            }
        });
    }

    private function constraintExists(string $table, string $constraint): bool
    {
        if (DB::connection($this->connection)->getDriverName() === 'sqlite') {
            return false;
        }
        return (bool) DB::connection($this->connection)->selectOne(
            <<<'SQL'
            SELECT 1
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_NAME = ?
            LIMIT 1
            SQL,
            [$table, $constraint]
        );
    }
};
