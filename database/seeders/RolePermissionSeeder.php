<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ownerRole = Role::create([
            'name' => 'owner'
        ]);

        $buyerRole = Role::create([
            'name' => 'buyer'
        ]);
        
        $user = User::create([
            'name' => 'pemilik',
            'email' => 'pemilik@gmail.com',
            'password' => bcrypt('123123123')
        ]);

        $user->assignRole($ownerRole);
    }
}