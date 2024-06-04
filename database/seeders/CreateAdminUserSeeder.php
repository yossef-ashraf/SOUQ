<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'techsolutionstuff',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123')
        ]);

        Role::create(['name' => 'user']);
        Role::create(['name' => 'merchant']);
        Role::create(['name' => 'admin-order']);
        Role::create(['name' => 'admin-merchant']);
        Role::create(['name' => 'admin']);
        $role = Role::create(['name' => 'super-admin']);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
