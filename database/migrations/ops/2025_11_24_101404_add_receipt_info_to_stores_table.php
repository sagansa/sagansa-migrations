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
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'receipt_header')) {
                            $table->text('receipt_header')->nullable();            }
            if (!Schema::hasColumn('stores', 'receipt_footer')) {
                            $table->text('receipt_footer')->nullable();            }
            if (!Schema::hasColumn('stores', 'email_receipt_logo')) {
                            $table->string('email_receipt_logo')->nullable();            }
            if (!Schema::hasColumn('stores', 'print_receipt_logo')) {
                            $table->string('print_receipt_logo')->nullable();            }
            if (!Schema::hasColumn('stores', 'address')) {
                            $table->text('address')->nullable();            }
            if (!Schema::hasColumn('stores', 'phone')) {
                            $table->string('phone')->nullable();            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'receipt_header',
                'receipt_footer',
                'email_receipt_logo',
                'print_receipt_logo',
                'address',
                'phone',
            ]);
        });
    }
};
