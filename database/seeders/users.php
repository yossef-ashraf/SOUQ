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
    'first_name'=>'Admin',
    'last_name'=>'Admin',
    'img'=>"",
    'email'=>'admin@admin.com',
    'password'=>Hash::make("12345678"),
    'country'=>'13s city',
    'city'=>'13s city',
    'street_adress'=>'13s city',
    'phone'=>'0233589090',
    'auth'=>'admin'
    //'auth'=>'user'
]);
}
}
