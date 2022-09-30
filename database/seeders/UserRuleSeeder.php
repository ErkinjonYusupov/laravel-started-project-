<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_rules')->truncate();
        $array = array(
            [
                'user_id' => 1,
                'rule_id' => 1
            ],
            [
                'user_id' => 1,
                'rule_id' => 2
            ],
            [
                'user_id' => 1,
                'rule_id' => 3
            ],
            [
                'user_id' => 1,
                'rule_id' => 4
            ],
            [
                'user_id' => 1,
                'rule_id' => 5
            ],
            [
                'user_id' => 1,
                'rule_id' => 6
            ],
        );
        DB::table('user_rules')->insert($array);
    }
}
