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
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'is_default')) {
                            $table->boolean('is_default')->default(false)->after('is_active');            }
        });

        // Mark existing cash payment methods as default
        DB::table('payment_methods')
            ->where('type', 'cash')
            ->update(['is_default' => true]);

        // Create default cash payment method for stores that don't have one
        $stores = DB::table('stores')->get();
        foreach ($stores as $store) {
            $hasCash = DB::table('payment_methods')
                ->where('store_id', $store->id)
                ->where('type', 'cash')
                ->exists();

            if (!$hasCash) {
                DB::table('payment_methods')->insert([
                    'id' => (string) Str::uuid(),
                    'store_id' => $store->id,
                    'type' => 'cash',
                    'name' => 'Tunai',
                    'is_active' => true,
                    'is_default' => true,
                    'require_proof' => false,
                    'details' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (Schema::hasColumn('payment_methods', 'is_default')) {
                            $table->dropColumn('is_default');            }
        });
    }
};
