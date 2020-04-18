<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        for ($i=0; $i < 5000; $i++) { 
            
            DB::table('users')->insert([
                'name'     => $faker->title,
                'email'    => Str::random(15).'@gmail.com',
                'password' => Hash::make($faker->password),
                'role'     => 'user',
            ]);
        }
    }
}
