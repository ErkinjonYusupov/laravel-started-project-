<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        $data = array(
            [
                'username' => 'yusupov',
                'full_name' => 'Erkinjon Yusupov',
                'phone' => '+998903666088',
                'password' => Hash::make('pass'),
                'organization_id'=> 1,
                'active' => true,
            ],
        );
        DB::table('users')->insert($data);
    }
}
