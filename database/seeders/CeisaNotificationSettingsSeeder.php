<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder default untuk notification_settings (fcm, telegram, email).
 */
class CeisaNotificationSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            [
                'channel' => 'fcm',
                'is_enabled' => true,
                'notify_normal' => false,
                'notify_urgent' => true,
                'target_recipient' => json_encode(['topic' => 'ceisa_pib']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'channel' => 'telegram',
                'is_enabled' => true,
                'notify_normal' => false,
                'notify_urgent' => true,
                'target_recipient' => json_encode(['chat_id' => env('TELEGRAM_CHAT_ID')]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'channel' => 'email',
                'is_enabled' => true,
                'notify_normal' => false,
                'notify_urgent' => true,
                'target_recipient' => json_encode(['to' => [env('MAIL_FROM_ADDRESS', 'finance@sagansa.id')]]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($rows as $row) {
            DB::connection('mysql_ceisa')
                ->table('notification_settings')
                ->updateOrInsert(
                    ['channel' => $row['channel']],
                    $row,
                );
        }
    }
}