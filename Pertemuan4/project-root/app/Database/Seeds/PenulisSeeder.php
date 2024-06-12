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
            'name' => 'Tomy Syarifudin',
            'address' => 'Jombang',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ],
        [ 
            'name' => 'Tony Stark',
            'address' => 'New York',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ],
        [ 
            'name' => 'Jihad Fisabilillah',
            'address' => 'Perak Jombang',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ],
        [ 
            'name' => 'Spiderman',
            'address' => 'New York',
            'created_at' => Time::now(),
            'updated_at' => Time::now()
        ],
        ]; 
        */
        $faker = \Faker\Factory::create('it_IT');
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'name' => $faker->name,
                'address' => $faker->address,
                'created_at' => Time::createFromTimestamp($faker->unixTime()),
                'updated_at' => Time::now()
            ];
            $this->db->table('penulis')->insert($data);
        }
    }
}