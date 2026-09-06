<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menggabungkan tiga tabel penjualan lama (sales_order_onlines,
 * sales_order_employees, sales_order_directs + tabel produk pivot-nya)
 * ke dalam satu tabel unified sales_orders + detail_sales_orders.
 *
 * Pemetaan channel mengikuti konvensi aplikasi baru:
 *   for = 1 -> direct, for = 2 -> employee, for = 3 -> online.
 *
 * Idempoten: setiap baris sumber yang sudah dipindah dicatat di tabel
 * legacy_sales_order_migration, sehingga migrasi bisa dijalankan ulang
 * tanpa menduplikasi data (aman di-tengah-jalan / resumable).
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    private const CHUNK = 500;

    /**
     * status lama sales_order_onlines (satu kolom) ->
     * [payment_status, delivery_status] pada sales_orders.
     * Label mengikuti resource admin baru: 1 belum diperiksa/belum dikirim,
     * 2 valid, 3 sudah dikirim, 4 siap dikirim, 5 perbaiki, 6 dikembalikan.
     */
    private const ONLINE_STATUS_MAP = [
        1 => [1, 1],
        2 => [2, 1],
        3 => [2, 3],
        4 => [2, 4],
        5 => [2, 5],
        6 => [2, 6],
    ];

    public function up(): void
    {
        if (!Schema::hasTable('legacy_sales_order_migration')) {
            Schema::create('legacy_sales_order_migration', function (Blueprint $table) {
                $table->string('source_table', 64);
                $table->unsignedBigInteger('source_id');
                $table->unsignedBigInteger('sales_order_id')->index();
                $table->timestamp('migrated_at')->useCurrent();

                $table->primary(['source_table', 'source_id']);
            });
        }

        $this->mergeOnlineOrders();
        $this->mergeEmployeeOrders();
        $this->mergeDirectOrders();
    }

    public function down(): void
    {
        if (!Schema::hasTable('legacy_sales_order_migration')) {
            return;
        }

        DB::table('detail_sales_orders')
            ->whereIn('sales_order_id', function ($query) {
                $query->select('sales_order_id')->from('legacy_sales_order_migration');
            })
            ->delete();

        DB::table('sales_orders')
            ->whereIn('id', function ($query) {
                $query->select('sales_order_id')->from('legacy_sales_order_migration');
            })
            ->delete();

        Schema::dropIfExists('legacy_sales_order_migration');
    }

    private function migratedIds(string $sourceTable): array
    {
        return DB::table('legacy_sales_order_migration')
            ->where('source_table', $sourceTable)
            ->pluck('source_id')
            ->all();
    }

    private function recordMapping(string $sourceTable, int $sourceId, int $salesOrderId): void
    {
        DB::table('legacy_sales_order_migration')->insert([
            'source_table' => $sourceTable,
            'source_id' => $sourceId,
            'sales_order_id' => $salesOrderId,
        ]);
    }

    /**
     * sales_order_onlines -> sales_orders (for = 3).
     */
    private function mergeOnlineOrders(): void
    {
        $source = 'sales_order_onlines';
        $done = array_flip($this->migratedIds($source));

        DB::table($source)
            ->orderBy('id')
            ->chunkById(self::CHUNK, function ($orders) use ($done, $source) {
                DB::transaction(function () use ($orders, $done, $source) {
                    foreach ($orders as $order) {
                        if (isset($done[$order->id])) {
                            continue;
                        }

                        $items = DB::table('product_sales_order_online')
                            ->where('sales_order_online_id', $order->id)
                            ->get();

                        $total = 0;
                        foreach ($items as $item) {
                            $total += (int) $item->quantity * (int) $item->price;
                        }

                        [$paymentStatus, $deliveryStatus] = self::ONLINE_STATUS_MAP[(int) $order->status] ?? [1, 1];

                        $notes = $order->notes;
                        if (!empty($order->customer_id)) {
                            // kolom customer_id tidak ada padanannya di sales_orders
                            $notes = trim('[customer #'.$order->customer_id.'] '.($notes ?? ''));
                        }

                        $salesOrderId = DB::table('sales_orders')->insertGetId([
                            'for' => '3',
                            'delivery_date' => $order->date,
                            'online_shop_provider_id' => $order->online_shop_provider_id,
                            'delivery_service_id' => $order->delivery_service_id,
                            'delivery_address_id' => $order->delivery_address_id,
                            'transfer_to_account_id' => null,
                            'image_payment' => $order->image,
                            'payment_status' => $paymentStatus,
                            'delivery_status' => $deliveryStatus,
                            'shipping_cost' => 0,
                            'store_id' => $order->store_id,
                            'receipt_no' => $order->receipt_no,
                            'image_delivery' => $order->image_sent,
                            'ordered_by_id' => $order->created_by_id,
                            'assigned_by_id' => $order->approved_by_id,
                            'notes' => $notes !== '' ? $notes : null,
                            'total_price' => $total,
                            'created_at' => $order->created_at,
                            'updated_at' => $order->updated_at,
                        ]);

                        foreach ($items as $item) {
                            DB::table('detail_sales_orders')->insert([
                                'product_id' => $item->product_id,
                                'quantity' => (int) $item->quantity,
                                'unit_price' => (int) $item->price,
                                'subtotal_price' => (int) $item->quantity * (int) $item->price,
                                'sales_order_id' => $salesOrderId,
                                'created_at' => $item->created_at ?? $order->created_at,
                                'updated_at' => $item->updated_at ?? $order->updated_at,
                            ]);
                        }

                        $this->recordMapping($source, $order->id, $salesOrderId);
                    }
                });
            });
    }

    /**
     * sales_order_employees -> sales_orders (for = 2).
     * Semua baris lama berstatus 1 (tidak pernah dimajukan), dipetakan
     * apa adanya: payment_status = 1 (belum diperiksa), delivery_status = 1.
     * customer & detail_customer dipindahkan ke delivery_addresses
     * (for = 2) dan ditautkan lewat delivery_address_id.
     */
    private function mergeEmployeeOrders(): void
    {
        $source = 'sales_order_employees';
        $done = array_flip($this->migratedIds($source));

        DB::table($source)
            ->orderBy('id')
            ->chunkById(self::CHUNK, function ($orders) use ($done, $source) {
                DB::transaction(function () use ($orders, $done, $source) {
                    foreach ($orders as $order) {
                        if (isset($done[$order->id])) {
                            continue;
                        }

                        $items = DB::table('product_sales_order_employee')
                            ->where('sales_order_employee_id', $order->id)
                            ->get();

                        $total = 0;
                        foreach ($items as $item) {
                            $total += (int) $item->quantity * (int) $item->unit_price;
                        }

                        $deliveryAddressId = DB::table('delivery_addresses')->insertGetId([
                            'for' => 2,
                            'name' => $order->customer,
                            'recipient_name' => $order->customer,
                            'address' => $order->detail_customer ?: '',
                            'created_at' => $order->created_at,
                            'updated_at' => $order->updated_at,
                        ]);

                        $salesOrderId = DB::table('sales_orders')->insertGetId([
                            'for' => '2',
                            'delivery_date' => $order->date,
                            'online_shop_provider_id' => null,
                            'delivery_service_id' => null,
                            'delivery_address_id' => $deliveryAddressId,
                            'transfer_to_account_id' => null,
                            'image_payment' => $order->image,
                            'payment_status' => 1,
                            'delivery_status' => 1,
                            'shipping_cost' => 0,
                            'store_id' => $order->store_id,
                            'receipt_no' => null,
                            'image_delivery' => null,
                            'ordered_by_id' => $order->user_id,
                            'assigned_by_id' => null,
                            'total_price' => $total,
                            'created_at' => $order->created_at,
                            'updated_at' => $order->updated_at,
                        ]);

                        foreach ($items as $item) {
                            DB::table('detail_sales_orders')->insert([
                                'product_id' => $item->product_id,
                                'quantity' => (int) $item->quantity,
                                'unit_price' => (int) $item->unit_price,
                                'subtotal_price' => (int) $item->quantity * (int) $item->unit_price,
                                'sales_order_id' => $salesOrderId,
                                'created_at' => $item->created_at ?? $order->created_at,
                                'updated_at' => $item->updated_at ?? $order->updated_at,
                            ]);
                        }

                        $this->recordMapping($source, $order->id, $salesOrderId);
                    }
                });
            });
    }

    /**
     * sales_order_directs -> sales_orders (for = 1).
     * Kode payment_status & delivery_status lama sudah sama dengan sistem
     * baru, jadi diteruskan apa adanya. Produk detail merujuk e_products
     * yang harus di-join dulu ke products.
     */
    private function mergeDirectOrders(): void
    {
        $source = 'sales_order_directs';
        $done = array_flip($this->migratedIds($source));

        DB::table($source)
            ->orderBy('id')
            ->chunkById(self::CHUNK, function ($orders) use ($done, $source) {
                DB::transaction(function () use ($orders, $done, $source) {
                    foreach ($orders as $order) {
                        if (isset($done[$order->id])) {
                            continue;
                        }

                        $items = DB::table('sales_order_direct_products as dp')
                            ->join('e_products as e', 'e.id', '=', 'dp.e_product_id')
                            ->where('dp.sales_order_direct_id', $order->id)
                            ->get([
                                'dp.quantity',
                                'dp.price',
                                'dp.amount',
                                'e.product_id',
                                'dp.created_at as item_created_at',
                                'dp.updated_at as item_updated_at',
                            ]);

                        $total = 0;
                        foreach ($items as $item) {
                            $total += (int) $item->amount;
                        }
                        $total -= (int) ($order->discounts ?? 0);

                        $notes = $order->notes;
                        if (!empty($order->delivery_location_id)) {
                            // kolom delivery_location_id tidak ada padanannya
                            $notes = trim('[delivery_location #'.$order->delivery_location_id.'] '.($notes ?? ''));
                        }

                        $salesOrderId = DB::table('sales_orders')->insertGetId([
                            'for' => '1',
                            'delivery_date' => $order->delivery_date,
                            'online_shop_provider_id' => null,
                            'delivery_service_id' => $order->delivery_service_id,
                            'delivery_address_id' => null,
                            'transfer_to_account_id' => $order->transfer_to_account_id,
                            'image_payment' => $order->image_transfer,
                            'payment_status' => $order->payment_status,
                            'delivery_status' => $order->delivery_status,
                            'shipping_cost' => $order->shipping_cost,
                            'store_id' => $order->store_id,
                            'receipt_no' => null,
                            'image_delivery' => $order->image_receipt,
                            'ordered_by_id' => $order->order_by_id,
                            'assigned_by_id' => $order->submitted_by_id,
                            'received_by' => $order->received_by,
                            'notes' => $notes !== '' ? $notes : null,
                            'total_price' => $total,
                            'created_at' => $order->created_at,
                            'updated_at' => $order->updated_at,
                        ]);

                        foreach ($items as $item) {
                            if (empty($item->product_id)) {
                                continue;
                            }

                            DB::table('detail_sales_orders')->insert([
                                'product_id' => $item->product_id,
                                'quantity' => (int) $item->quantity,
                                'unit_price' => (int) $item->price,
                                'subtotal_price' => (int) $item->amount,
                                'sales_order_id' => $salesOrderId,
                                'created_at' => $item->item_created_at ?? $order->created_at,
                                'updated_at' => $item->item_updated_at ?? $order->updated_at,
                            ]);
                        }

                        $this->recordMapping($source, $order->id, $salesOrderId);
                    }
                });
            });
    }
};
