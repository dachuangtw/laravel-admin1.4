<?php

use Illuminate\Database\Seeder;

class AdminRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_roles')->delete();
        //ROLES
        DB::table('admin_roles')->insert([
            ['id' => '1',
            'name' => '超級管理員',
            'slug' => 'administrator',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
            ['id' => '2',
            'name' => '總倉倉管',
            'slug' => 'SuperWarehouse',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
            ['id' => '3',
            'name' => '一般倉管人員',
            'slug' => 'Warehouse',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
            ['id' => '4',
            'name' => '進貨入員',
            'slug' => '	Check',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
            ['id' => '5',
            'name' => '資料輸入員',
            'slug' => 'Keyin',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
            ['id' => '6',
            'name' => '業務主管',
            'slug' => 'Supervisior',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
            ['id' => '7',
            'name' => '老闆',
            'slug' => 'Boss',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
            ['id' => '8',
            'name' => '盤點人員',
            'slug' => 'Inventory',
            'created_at' => date('Ymd'),
            'updated_at' => date('Ymd'),
            ],
        ]);
    }
}
