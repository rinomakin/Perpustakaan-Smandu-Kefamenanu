<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PengaturanWebsiteSeeder::class,
            JurusanSeeder::class,
            KelasSeeder::class,
            KategoriBukuSeeder::class,
            JenisBukuSeeder::class,
            SumberBukuSeeder::class,
            PenerbitSeeder::class,
            PenulisSeeder::class,
            UserSeeder::class,
        ]);
    }
}
