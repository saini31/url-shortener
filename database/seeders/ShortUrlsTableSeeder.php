<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShortUrlsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('short_urls')->insert([
            [
                
                'user_id' => 2, // Make sure this user exists in the users table
                'long_url' => 'https://www.example.com',
                'short_code' => 'abc123',
                'hits' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
               
                'user_id' => 3,
                'long_url' => 'https://www.test.com',
                'short_code' => 'xyz789',
                'hits' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
