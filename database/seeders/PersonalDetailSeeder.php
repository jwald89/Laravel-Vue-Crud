<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class PersonalDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $chunkSize = 500; // Adjust the chunk size as needed

        $totalRecords = 300000;
        $totalChunks = ceil($totalRecords / $chunkSize);

        for ($i = 0; $i < $totalChunks; $i++) {
            $data = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $data[] = [
                    'profile_img' => 'https://source.unsplash.com/random/200x200',
                    'first_name' => $faker->firstName,
                    'middle_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'address' => $faker->address,
                    'gender' => $faker->randomElement(['male', 'female']),
                    'contact_no' => $faker->phoneNumber,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('personal_details')->insert($data);
        }
    }
}
