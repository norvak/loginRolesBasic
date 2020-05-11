<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'testing',
            'last_name' => 'test',
            'email' => 'testing@testing.com',
            'username' => 'admin',
            'password' => 'admin'
        ]);
    }
}