<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'Admin',
            'email' => 'super@gmail.com',
            'password' => Hash::make("12345678"),
            'entriprise_id' => 1,
            'status' => true,
        ]);

        DB::table('entreprises')->insert([
            'matricule' => '0254566655422558525',
            'name' => 'Mairie',
            'adresse' => '95, Rue N° 173 KW Tokoin-Wuiti, BP 2469, Lomé',
            'telephone' => '22 26 22 04',
            'photo_url' => 'profiles/16277682840557hzma.jpeg',
            'status' => true,
            'role' => '1',



        ]);
    }
}
