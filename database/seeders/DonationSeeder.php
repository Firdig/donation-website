<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        Donation::create([
            'title' => 'Bantu Korban Bencana Alam',
            'description' => 'Mari kita bersama-sama membantu saudara kita yang terkena bencana alam. Setiap rupiah yang Anda berikan akan sangat berarti untuk mereka.',
            'target_amount' => 100000000,
            'current_amount' => 25000000,
            'status' => 'active',
            'end_date' => now()->addMonths(3),
        ]);

        Donation::create([
            'title' => 'Pendidikan Anak Yatim',
            'description' => 'Berikan kesempatan pendidikan terbaik untuk anak-anak yatim piatu agar mereka memiliki masa depan yang cerah.',
            'target_amount' => 50000000,
            'current_amount' => 15000000,
            'status' => 'active',
            'end_date' => now()->addMonths(6),
        ]);

        Donation::create([
            'title' => 'Pembangunan Masjid',
            'description' => 'Mari bergotong royong membangun tempat ibadah untuk umat Muslim di daerah terpencil.',
            'target_amount' => 200000000,
            'current_amount' => 75000000,
            'status' => 'active',
            'end_date' => now()->addYear(),
        ]);
    }
}