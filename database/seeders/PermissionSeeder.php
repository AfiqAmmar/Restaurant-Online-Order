<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role1 = Role::create(['name' => 'master-admin']);
        $role2 = Role::create(['name' => 'cashier']);
        $role3 = Role::create(['name' => 'waiter']);
        $role4 = Role::create(['name' => 'kitchen-staff']);

    }
}
