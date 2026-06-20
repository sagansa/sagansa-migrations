<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Log API H2H (outbound & inbound) untuk audit dan debugging.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        if (!Schema::connection($this->connection)->hasTable('ceisa_api_logs')) {
            Schema::connection($this->connection)->create('ceisa_api_logs', function (Blueprint $table) {
                $table->id();
                $table->enum('direction', ['outbound', 'inbound']);
                $table->string('endpoint', 500);
                $table->string('method', 10);
                $table->json('request_payload')->nullable();
                $table->json('response_payload')->nullable();
                $table->unsignedSmallInteger('response_code')->nullable();
                $table->unsignedInteger('duration_ms')->nullable();
                $table->string('error_message', 500)->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['direction', 'created_at']);
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('ceisa_api_logs');
    }
};