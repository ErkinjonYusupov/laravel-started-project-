<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rules')->truncate();
        $data = array(
            [
                'title' => 'Organizatsiyalarni ko\'rish',
                'code' => 'organization_show',
            ],
            [
                'title' => 'Organizatsiyalarni qo\'shish',
                'code' => 'organization_add',
            ],
            [
                'title' => 'Organizatsiyalarni tahrirlash',
                'code' => 'organization_edit',
            ],
            [
                'title' => 'Foydalanuvchilarni  ko\'rish',
                'code' => 'users_show',
            ],
            [
                'title' => 'Foydalanuvchilarni  qo\'shish',
                'code' => 'users_add',
            ],
            [
                'title' => 'Foydalanuvchilarni tahrirlash',
                'code' => 'users_edit',
            ],
        );
        DB::table('rules')->insert($data);
    }
}
