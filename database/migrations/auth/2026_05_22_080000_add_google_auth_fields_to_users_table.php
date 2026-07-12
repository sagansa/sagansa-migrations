<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_auth';

    public function up(): void
    {
        Schema::connection($this->connection)->table('users', function (Blueprint $table) {
            if (! Schema::connection($this->connection)->hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }

            if (! Schema::connection($this->connection)->hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }

            if (! Schema::connection($this->connection)->hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('avatar');
            }
        });

        // Raw 'ALTER TABLE ... MODIFY' is MySQL DDL unsupported by SQLite.
        // Skip on SQLite — password nullability is a non-critical schema refinement for tests.
        if (DB::connection($this->connection)->getDriverName() !== 'sqlite') {
            DB::connection($this->connection)->statement('ALTER TABLE users MODIFY password VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('users', function (Blueprint $table) {
            if (Schema::connection($this->connection)->hasColumn('users', 'phone_number')) {
                $table->dropColumn('phone_number');
            }

            if (Schema::connection($this->connection)->hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }

            if (Schema::connection($this->connection)->hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
        });

        if (DB::connection($this->connection)->getDriverName() !== 'sqlite') {
            DB::connection($this->connection)->statement('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL');
        }
    }
};
