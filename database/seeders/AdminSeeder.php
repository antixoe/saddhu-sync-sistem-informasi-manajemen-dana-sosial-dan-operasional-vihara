<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@saddhusync.local'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        
        if (!$user->member) {
            Member::create([
                'user_id' => $user->id,
                'member_id' => 'ADM-' . date('YmdHis'),
                'join_date' => now(),
                'qr_code_token' => \Illuminate\Support\Str::random(20),
            ]);
        }
    }
}
