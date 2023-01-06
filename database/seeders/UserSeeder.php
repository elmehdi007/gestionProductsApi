<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;
use App\Helpers\Enum\RoleIds;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
                [
                    'id' => (string) Str::uuid(),
                    'role_id' => RoleIds::Admin->value,
                    'lname' => 'Mohammed',
                    'fname' => 'KARIM',
                    'email' => 'mohammed@mail.com',
                    'phone' => rand(1111111,9999999),
                    'password' => Hash::make("P@assword123")
                ],
                [
                    'id' => (string) Str::uuid(),
                    'role_id' => RoleIds::Admin->value,
                    'lname' => 'SADIK',
                    'fname' => 'Haytam',
                    'email' => 'haytam@mail.com',
                    'phone' => rand(1111111,9999999),
                    'password' => Hash::make("P@assword123")
                ]
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('users')->insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
