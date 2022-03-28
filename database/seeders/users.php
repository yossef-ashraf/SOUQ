<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
User::create([
'name'=>'Admin',
'email'=>'admin@admin.com',
'password'=>Hash::make("12345678"),
'adress'=>'13s city',
'phone'=>'0233589090',
'auth'=>'admin'
//'auth'=>'user'
]);
}
}
