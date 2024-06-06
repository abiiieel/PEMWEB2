<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

use CodeIgniter\I18n\Time;

class PenulisSeeder extends Seeder
{
    public function run()
    {
        /*
        $data = [
            [
                'name' => 'Masashi Kishimoto',
                'address' => 'Jl. Naruto Shippuden',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'name' => 'Muhammad Sanjaya',
                'address' => 'Jl. Mawar No. 21',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'name' => 'Subarjo',
                'address' => 'Jl. Masjid Agung',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
        ];
        */

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'name' => $faker->name,
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'company' => $faker->company,
                'created_at' => Time::createFromTimestamp($faker->unixTime()),
                'updated_at' => Time::now()
            ];
            $this->db->table('penulis')->insert($data);
        }
    }
}
