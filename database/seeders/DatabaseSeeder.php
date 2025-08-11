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
            RoleSeeder::class,
            PermissionSeeder::class,
            JurusanSeeder::class,
            KelasSeeder::class,
            KategoriBukuSeeder::class,
            JenisBukuSeeder::class,
            SumberBukuSeeder::class,
            UserSeeder::class,
            AssignPermissionsSeeder::class,
            PengembalianSeeder::class,
        ]);
    }
}
