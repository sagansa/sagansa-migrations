<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        DB::connection($this->connection)
            ->table('users')
            ->where(function ($query) {
                $query->whereNull('uuid')
                    ->orWhere('uuid', '');
            })
            ->orderBy('id')
            ->select('id')
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    DB::connection($this->connection)
                        ->table('users')
                        ->where('id', $user->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });
    }

    public function down(): void
    {
        //
    }
};
