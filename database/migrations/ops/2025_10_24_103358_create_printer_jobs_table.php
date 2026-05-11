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
        Schema::create('printer_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid('printer_id');
            $table->uuid('order_id')->nullable(); // nullable for test prints
            $table->json('payload'); // JSON payload for print job
            $table->enum('status', ['pending', 'printing', 'printed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
            
            $table->foreign('printer_id')->references('id')->on('printers')->onDelete('cascade');
            // Foreign key to orders table will be added after the orders table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_jobs');
    }
};
