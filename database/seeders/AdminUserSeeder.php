<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'ditech@cloudphone.com',
            'password' => Hash::make('Ditechcloudphone'), // Mã hóa mật khẩu
            'is_admin' => true, // Đặt quyền admin
        ]);
    }
}
