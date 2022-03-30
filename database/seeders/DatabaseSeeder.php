<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RadiacaoTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            RadiacaoTableSeeder::class,
           /*  RadiacaoTableSeeder2::class,
            RadiacaoTableSeeder3::class,
            RadiacaoTableSeeder4::class,
            RadiacaoTableSeeder5::class,
            RadiacaoTableSeeder6::class,
            RadiacaoTableSeeder7::class, */
        ]);
    }
}
