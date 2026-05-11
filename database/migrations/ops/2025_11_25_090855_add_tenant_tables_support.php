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
        Schema::table('tables', function (Blueprint $table) {
            // Add tenant_id for food court mode
            $table->foreignUuid('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            
            // Add zone support
            $table->foreignUuid('zone_id')->nullable()->after('tenant_id')->constrained('table_zones')->nullOnDelete();
            
            // Add QR code
            $table->string('qr_code', 255)->nullable()->unique()->after('table_number');
            
            // Make store_id nullable (for tenant-owned tables)  
            $table->uuid('store_id')->nullable()->change();
        });


        // Generate QR codes for existing tables
        DB::statement("
            UPDATE tables 
            SET qr_code = CONCAT('TBL-', UPPER(SUBSTRING(id, 1, 8)))
            WHERE qr_code IS NULL
        ");

        // Add constraint: must have either store_id OR tenant_id (not both, not neither)
        DB::statement('
            ALTER TABLE tables ADD CONSTRAINT chk_table_owner CHECK (
                (store_id IS NOT NULL AND tenant_id IS NULL) OR
                (store_id IS NULL AND tenant_id IS NOT NULL)
            )
        ');
    }

    public function down(): void
    {
        // Drop constraint first
        DB::statement('ALTER TABLE tables DROP CONSTRAINT IF EXISTS chk_table_owner');
        
        Schema::table('tables', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
            $table->dropConstrainedForeignId('zone_id');
            $table->dropColumn('qr_code');
            
            // Restore store_id as NOT NULL
            $table->uuid('store_id')->nullable(false)->change();
        });
    }
};
