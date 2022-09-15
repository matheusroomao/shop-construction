<?php

namespace Database\Seeders;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Matheus Romao';
        $user->email = 'matheus@gmail.com';
        $user->phone = '(85) 9 12345-3406';
        $user->password = 'asdfasdf';
        $user->type = 'PROVIDER';
        $user->save();


        $user = new User();
        $user->name = 'Joao Silva';
        $user->email = 'joao@gmail.com';
        $user->phone = '(88) 9 12345-3406';
        $user->password = 'asdfasdf';
        $user->type = 'BUYER';
        $user->save();
    }
}
