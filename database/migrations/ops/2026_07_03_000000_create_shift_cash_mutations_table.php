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

        if (! $schema->hasTable('shift_cash_mutations')) {
            $schema->create('shift_cash_mutations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('shift_id')->index();
                $table->string('type', 32)->index(); // expense, handover
                $table->decimal('amount', 15, 2);
                $table->text('note');
                $table->uuid('created_by')->index();
                $table->timestamps();

                $table->foreign('shift_id')
                      ->references('id')
                      ->on('shifts')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        $schema = Schema::connection($this->connection);
        $schema->dropIfExists('shift_cash_mutations');
    }
};
