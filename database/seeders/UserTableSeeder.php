<?php

namespace Database\Seeders;

use App\Models\User;
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
                'role' => User::IS_ADMIN
            ],
        ];

        DB::table('users')->insert($data);
    }
}
