<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed fund categories
        $this->call(FundCategorySeeder::class);

        // Seed settings for donations
        $this->call(SettingSeeder::class);

        // create basic roles table entries so admins can manage them
        if (\App\Models\Role::count() === 0) {
            \App\Models\Role::insert([
                ['name' => 'admin', 'label' => 'Administrator', 'description' => 'Full access to the system', 'created_at'=>now(),'updated_at'=>now()],
                ['name' => 'officer', 'label' => 'Officer', 'description' => 'Can manage operational data', 'created_at'=>now(),'updated_at'=>now()],
                ['name' => 'member', 'label' => 'Member', 'description' => 'Regular temple member', 'created_at'=>now(),'updated_at'=>now()],
            ]);
        }

        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@saddhusync.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create officers
        User::factory(2)->create([
            'role' => 'officer',
        ]);

        // Create regular members
        User::factory(10)->create([
            'role' => 'member',
        ]);
    }
}
