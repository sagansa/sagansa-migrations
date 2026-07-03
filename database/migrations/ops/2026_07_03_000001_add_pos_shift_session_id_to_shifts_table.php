<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_ops';

    public function up(): void
    {
        $schema = Schema::connection($this->connection);

        if ($schema->hasTable('shifts') && ! $schema->hasColumn('shifts', 'pos_shift_session_id')) {
            $schema->table('shifts', function (Blueprint $table) {
                $table->uuid('pos_shift_session_id')->nullable()->after('store_id')->index();

                $table->foreign('pos_shift_session_id')
                      ->references('id')
                      ->on('pos_shift_sessions')
                      ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);

        if ($schema->hasTable('shifts') && $schema->hasColumn('shifts', 'pos_shift_session_id')) {
            $schema->table('shifts', function (Blueprint $table) {
                $table->dropForeign(['pos_shift_session_id']);
                $table->dropColumn('pos_shift_session_id');
            });
        }
    }
};
