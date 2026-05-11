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
            $table->text('receipt_header')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->string('email_receipt_logo')->nullable();
            $table->string('print_receipt_logo')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
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
