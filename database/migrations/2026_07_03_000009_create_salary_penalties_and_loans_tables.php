<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table for manual penalties
        if (!Schema::hasTable('salary_penalties')) {
            Schema::create('salary_penalties', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('tenant_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->decimal('amount', 15, 2);
                $table->string('description');
                $table->date('date');
                $table->unsignedBigInteger('monthly_salary_id')->nullable()->index();
                $table->timestamps();

                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                }
            });
        }

        // 2. Table for employee loans (Kasbon)
        if (!Schema::hasTable('employee_loans')) {
            Schema::create('employee_loans', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->uuid('tenant_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->decimal('amount', 15, 2);
                $table->string('guarantee')->nullable(); // Jaminan kasbon (misal BPKB, Ijazah, dll)
                $table->string('description');
                $table->date('loan_date');
                $table->integer('installment_count')->default(1);
                $table->decimal('installment_amount', 15, 2);
                $table->tinyInteger('status')->default(1); // 1 = active, 2 = paid
                $table->timestamps();

                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                }
            });
        }

        // 3. Table for details of installments
        if (!Schema::hasTable('loan_installments')) {
            Schema::create('loan_installments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('employee_loan_id')->index();
                $table->unsignedBigInteger('monthly_salary_id')->nullable()->index();
                $table->decimal('amount', 15, 2);
                $table->date('due_date');
                $table->tinyInteger('status')->default(1); // 1 = pending, 2 = paid, 3 = deferred
                $table->timestamps();

                $table->foreign('employee_loan_id')->references('id')->on('employee_loans')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_installments');
        Schema::dropIfExists('employee_loans');
        Schema::dropIfExists('salary_penalties');
    }
};
