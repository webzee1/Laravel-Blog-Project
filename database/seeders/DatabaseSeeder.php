<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Calling Seeder
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
    }
}
