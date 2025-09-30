<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Picture;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Ayu', 'Sinta', 'Rina', 'Dika', 'Rama', 'Bima', 'Adi', 'Putri', 'Nisa', 'Reza',
            'Sari', 'Andi', 'Budi', 'Citra', 'Dina', 'Eka', 'Fira', 'Gita', 'Hani', 'Indra',
            'Joko', 'Kiki', 'Lina', 'Maya', 'Nina', 'Oscar', 'Pita', 'Qori', 'Rudi', 'Susi',
            'Tina', 'Umar', 'Vina', 'Wati', 'Xena', 'Yani', 'Zara', 'Ahmad', 'Bella', 'Caca',
            'Dede', 'Eva', 'Fani', 'Gina', 'Hadi', 'Ika', 'Jihan', 'Koko', 'Lala', 'Mira',
            'Nana', 'Ola', 'Pina', 'Ria', 'Sasa', 'Tika', 'Uci', 'Vivi', 'Wina', 'Yola',
            'Zizi', 'Agus', 'Beta', 'Cici', 'Doni', 'Eli', 'Fika', 'Gina', 'Heni', 'Ira',
            'Jaka', 'Kina', 'Lola', 'Maya', 'Nita', 'Omar', 'Pina', 'Rika', 'Sani', 'Tina',
            'Umi', 'Vera', 'Wati', 'Yani', 'Zita', 'Ari', 'Budi', 'Cinta', 'Dika', 'Eka'
        ];

        $locations = [
            'Jakarta', 'Depok', 'Tangerang', 'Bekasi', 'Bandung', 'Surabaya', 'Yogyakarta',
            'Semarang', 'Medan', 'Makassar', 'Denpasar', 'Palembang', 'Padang', 'Malang',
            'Solo', 'Balikpapan', 'Samarinda', 'Pontianak', 'Manado', 'Jayapura'
        ];

        for ($i = 1; $i <= 80; $i++) {
            // Age distribution: 80% between 20-35, 20% between 18-19 and 36-40
            $age = rand(1, 10) <= 8 ? rand(20, 35) : (rand(1, 2) == 1 ? rand(18, 19) : rand(36, 40));
            
            $person = Person::create([
                'name' => $names[array_rand($names)],
                'age' => $age,
                'location' => $locations[array_rand($locations)],
                'popularity_notified_at' => null,
                'last_notified_like_count' => 0
            ]);

            // Add 3-5 pictures per person
            $pictureCount = rand(3, 5);
            for ($j = 1; $j <= $pictureCount; $j++) {
                // Use person name as seed for consistent images
                $imageSeed = $person->name . "-{$i}-{$j}";
                Picture::create([
                    'person_id' => $person->id,
                    'url' => "https://picsum.photos/seed/{$imageSeed}/600/800",
                    'order' => $j
                ]);
            }
        }
    }
}
