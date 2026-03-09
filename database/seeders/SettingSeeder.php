<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'donation_qr_code',
                'value' => '/images/qr-donation.svg', // Placeholder path for QR code
            ],
            [
                'key' => 'donation_bank_details',
                'value' => "BANK CENTRAL ASIA (BCA)\nAccount Number: 1234567890\nAccount Name: VIhara Saddhu\nBranch: Jakarta Pusat\n\nBANK MANDIRI\nAccount Number: 0987654321\nAccount Name: Yayasan Vihara Saddhu\nBranch: Jakarta Selatan\n\nBANK BRI\nAccount Number: 1122334455\nAccount Name: Vihara Saddhu Foundation\nBranch: Jakarta Timur",
            ],
            [
                'key' => 'donation_virtual_accounts',
                'value' => "GOPAY: 081234567890 (Vihara Saddhu)\nOVO: 081234567891 (Vihara Saddhu)\nDANA: 081234567892 (Vihara Saddhu)\n\nVirtual Account Numbers:\nBCA VA: 1234567890123456\nMandiri VA: 8876543210987654\nBRI VA: 0021122334455667",
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}