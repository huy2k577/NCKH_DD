<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Tạo role
    $superAdmin = Role::create(['name' => 'super_admin']);
    $staff = Role::create(['name' => 'staff']);
 
    // Tạo permission
    $create = Permission::create(['name' => 'create_lichthi']);
    $edit = Permission::create(['name' => 'edit_lichthi']);
    $delete = Permission::create(['name' => 'delete_lichthi']);
    $index = Permission::create(['name' => 'index_lichthi']);
    $view = Permission::create(['name' => 'view_lichthi']);
    $pcct = Permission::create(['name' => 'pcct_lichthi']);
    $admin= Permission::create(['name' => 'admin_lichthi']);

    // Gán permission cho role
    $superAdmin->permissions()->attach([$create->id, $edit->id, $delete->id, $view->id, $admin->id, $index->id]);
    $staff->permissions()->attach([$pcct->id, $admin->id, $index->id]);

    // Tạo admin
    $adminsuper = User::create([
        'name' => 'Super Admin',
        'email' => 'superadmin@gmail.com', 
        'password' => Hash::make('123456')
    ]);
    $admin = User::create([
        'name' => 'staff',
        'email' => 'admin@gmail.com', 
        'password' => Hash::make('12345678')
    ]);

    // Gán role cho admin
    $adminsuper->roles()->attach($superAdmin->id);
    $admin->roles()->attach($staff->id);
    }
}
