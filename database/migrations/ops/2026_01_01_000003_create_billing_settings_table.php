<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Subscription & Billing — Tabel: billing_settings
 *
 * Konfigurasi global payment provider. 1 baris saja (singleton).
 * Super-admin pilih Xendit/Midtrans + simpan API key (terenkripsi) via UI.
 * Switch provider = update active_provider saja, tanpa deploy.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ops';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('billing_settings')) {
            Schema::connection($this->connection)->create('billing_settings', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->enum('active_provider', ['xendit', 'midtrans'])->default('xendit');
                // Xendit credentials
                $table->text('xendit_secret_key')->nullable();
                $table->text('xendit_verify_key')->nullable();
                // Midtrans credentials
                $table->text('midtrans_server_key')->nullable();
                $table->text('midtrans_client_key')->nullable();
                $table->boolean('midtrans_is_production')->default(false);
                // Webhook
                $table->string('webhook_secret', 100)->nullable()->comment('Token validasi webhook');
                $table->uuid('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('billing_settings');
    }
};
