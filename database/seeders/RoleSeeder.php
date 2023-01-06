<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Helpers\Enum\RoleIds;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {        
        $data = [
                    ['id'=> RoleIds::Admin->value, 'name' => "admin"],
                ];
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('roles')->insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
