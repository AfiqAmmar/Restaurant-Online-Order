<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'fname' => 'Master',
            'lname' => 'Admin',
            'address' => 'Sample Address',
            'start_date' => date('Y-m-d', strtotime('2022-04-02')),
            'phone_number' => '0123456789',
            'gender' => 'Male',
            'salary' => '1234',
            'email' => 'masteradmin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'Master-Admin'
        ]);

        $user->assignRole('master-admin');
    }
}
