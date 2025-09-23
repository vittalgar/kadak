<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the ID for the 'Admin' role
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');

        // Create the default Admin user
        DB::table('users')->insert([
            'name' => 'Bharath Admin',
            'email' => 'admin@bharathgroup.com',
            'password' => Hash::make('Admin@123'), // Default password is "password"
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get the ID for the 'Dealer' role
        // $dealerRoleId = DB::table('roles')->where('name', 'Dealer')->value('id');

        // Create a default Dealer user
        // DB::table('users')->insert([
        //     'name' => 'Kadak Dealer',
        //     'email' => 'dealer@bharathgroup.com',
        //     'password' => Hash::make('password'), // Default password is "password"
        //     'role_id' => $dealerRoleId,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}
