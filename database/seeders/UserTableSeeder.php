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
                'name'      => 'admin',
                'email'     => 'example@example.com',
                'password'  => Hash::make('admin'),
                'role' => User::IS_ADMIN
            ],
        ];

        DB::table('users')->insert($data);
    }
}
