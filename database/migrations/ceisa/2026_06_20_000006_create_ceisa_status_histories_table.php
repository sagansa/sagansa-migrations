<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Log riwayat status PIB (ceisa_status_histories).
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('ceisa_status_histories')) {
            Schema::connection($this->connection)->create('ceisa_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pib_document_id')->constrained()->cascadeOnDelete();
                $table->string('status', 32);
                $table->string('urgency', 16)->default('normal'); // normal|urgent
                $table->json('raw_payload')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['pib_document_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('ceisa_status_histories');
    }
};