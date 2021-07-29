<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'      => 'ws396',
                'email'     => 'ws@sentro.ru',
                'password'  => Hash::make('s93jf7wjd'),
            ],
        ];

        DB::table('users')->insert($data);
    }
}
