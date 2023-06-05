<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make(12345678),
            'phone' => '0101010' ,
            // 'address' => 'address' ,
            'auth' =>'admin'
        ]);
                 User::create([
                    'firstname' => 'test',
                    'lastname' => 'test',
                    'email' => 'test@test.com',
                    'password' => Hash::make(12345678),
                    'phone' => '0202020' ,
                    // 'address' => 'address' ,
                    'auth' =>'user'
                 ]);



    }
}
