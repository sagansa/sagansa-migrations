<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 2 (revisi) — Tambah field OAuth2 pada ceisa_credentials.
 *
 * Mengikuti kebijakan auth VERIFIED dari OpenAPI Portal Bea Cukai:
 *   1) API Key via header `beacukai-api-key` (sudah ada kolom `api_key`)
 *   2) OAuth2 Bearer token via `Authorization: Bearer <token>` (baru — butuh
 *      client_id, client_secret, token_url, dan cache token terkini).
 *
 * token_url disimpan per-credential agar satu instalasi bisa punya beberapa
 * akun BC (sandbox vs production) dengan token endpoint berbeda.
 * access_token + expires_at di-cache agar tidak request token baru tiap call.
 */
return new class extends Migration
{
    protected $connection = 'mysql_ceisa';

    public function up(): void
    {
        Schema::connection($this->connection)->table('ceisa_credentials', function (Blueprint $table) {
            // OAuth2 client credentials (disimpan encrypted via cast di model)
            $table->text('client_id')->nullable()->after('api_key');
            $table->text('client_secret')->nullable()->after('client_id');

            // Token endpoint per akun (e.g. https://apis-gw.beacukai.go.id/oauth2/token)
            $table->string('token_url', 255)->nullable()->after('client_secret');

            // Token cache (encrypted). NULL = belum pernah request / perlu refresh.
            $table->text('access_token')->nullable()->after('token_url');
            $table->timestamp('token_expires_at')->nullable()->after('access_token');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('ceisa_credentials', function (Blueprint $table) {
            $table->dropColumn([
                'client_id',
                'client_secret',
                'token_url',
                'access_token',
                'token_expires_at',
            ]);
        });
    }
};