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
        if (!Schema::hasTable('printer_configs')) {
                    Schema::create('printer_configs', function (Blueprint $table) {
                        $table->id();
                        $table->uuid('store_id')->index();
                        $table->enum('type', ['bluetooth', 'wifi'])->default('bluetooth');
                        $table->string('name')->nullable();
            
                        // Bluetooth specific fields
                        $table->string('device_name')->nullable();
                        $table->string('device_address')->nullable();
            
                        // WiFi/LAN specific fields
                        $table->string('ip_address')->nullable();
                        $table->unsignedInteger('port')->default(9100);
            
                        // Common settings
                        $table->enum('paper_width', ['58mm', '80mm'])->default('80mm');
                        $table->boolean('is_active')->default(true);
            
                        $table->timestamps();
                    });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_configs');
    }
};
