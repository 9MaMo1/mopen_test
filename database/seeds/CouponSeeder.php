<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $codes = '';       

        for ($i=0; $i < 5000; $i++) { 
            
           $codes .= $faker->bankAccountNumber."\n";
 
        }

        file_put_contents(storage_path('test.txt'), $codes);
    }
}
