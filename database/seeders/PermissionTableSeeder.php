<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'product-list',
            'product-create',
            'product-edit',
            'product-delete',

            'order-list',
            'order-create',
            'order-edit',
            'order-delete',

            'order-package-list',
            'order-package-create',
            'order-package-edit',
            'order-package-delete',

            'order-tracking-list',
            'order-tracking-create',
            'order-tracking-edit',
            'order-tracking-delete',
         ];

         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
