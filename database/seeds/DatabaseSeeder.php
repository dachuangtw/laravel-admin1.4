<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //權限
        DB::table('admin_permissions')->insert([
            ['name' => '倉庫_新刪修',
             'slug' => 'warehouse_editor'],
            ['name' => '商品_新刪修',
             'slug' => 'product_editor'],
        ]);
        WAREHOUSE
        DB::table('warehouse')->insert([
            ['w_name'   => '新竹倉'],
            ['w_name'   => '台中倉'],
            ['w_name'   => '雲林倉'],
            ['w_name'   => '高雄倉'],
        ]);
        //MENU
        DB::table('admin_menu')->insert([
            ['id' => '1',
            'parent_id' => '0',
            'order' => '1',
            'title' => '首頁',
            'icon' => 'fa-home',
            'uri' => '/',
            'created_at' => NULL,
            'updated_at' => '2017-11-20 16:36:41',
            ],
            ['id' => '2',
            'parent_id' => '0',
            'order' => '2',
            'title' => 'Admin帳號管理',
            'icon' => 'fa-tasks',
            'uri' => '',
            'created_at' => NULL,
            'updated_at' => '2017-12-08 02:52:00',
            ],
            ['id' => '3',
            'parent_id' => '2',
            'order' => '3',
            'title' => '管理員帳號',
            'icon' => 'fa-users',
            'uri' => 'auth/users',
            'created_at' => NULL,
            'updated_at' => '2017-12-08 02:52:00',
            ],
            ['id' => '4',
            'parent_id' => '2',
            'order' => '4',
            'title' => '角色',
            'icon' => 'fa-user',
            'uri' => 'auth/roles',
            'created_at' => NULL,
            'updated_at' => '2017-12-08 02:52:00',
            ],
            ['id' => '5',
            'parent_id' => '2',
            'order' => '5',
            'title' => '權限',
            'icon' => 'fa-ban',
            'uri' => 'auth/permissions',
            'created_at' => NULL,
            'updated_at' => '2017-12-08 02:52:00',
            ],
            ['id' => '6',
            'parent_id' => '2',
            'order' => '6',
            'title' => 'Menu',
            'icon' => 'fa-bars',
            'uri' => 'auth/menu',
            'created_at' => NULL,
            'updated_at' => '2017-12-08 02:52:00',
            ],
            ['id' => '7',
            'parent_id' => '2',
            'order' => '7',
            'title' => 'Operation log',
            'icon' => 'fa-history',
            'uri' => 'auth/logs',
            'created_at' => NULL,
            'updated_at' => '2017-12-08 02:52:00',
            ],
            ['id' => '8',
            'parent_id' => '0',
            'order' => '8',
            'title' => '業務管理',
            'icon' => 'fa-users',
            'uri' => 'users',
            'created_at' => '2017-11-16 03:19:29',
            'updated_at' => '2017-12-08 03:18:38',
            ],
            ['id' => '9',
            'parent_id' => '0',
            'order' => '27',
            'title' => 'Helpers',
            'icon' => 'fa-gears',
            'uri' => '',
            'created_at' => '2017-11-16 15:54:04',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '10',
            'parent_id' => '9',
            'order' => '28',
            'title' => 'Scaffold',
            'icon' => 'fa-keyboard-o',
            'uri' => 'helpers/scaffold',
            'created_at' => '2017-11-16 15:54:04',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '11',
            'parent_id' => '9',
            'order' => '29',
            'title' => 'Database terminal',
            'icon' => 'fa-database',
            'uri' => 'helpers/terminal/database',
            'created_at' => '2017-11-16 15:54:04',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '12',
            'parent_id' => '9',
            'order' => '30',
            'title' => 'Laravel artisan',
            'icon' => 'fa-terminal',
            'uri' => 'helpers/terminal/artisan',
            'created_at' => '2017-11-16 15:54:05',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '13',
            'parent_id' => '9',
            'order' => '31',
            'title' => 'Routes',
            'icon' => 'fa-list-alt',
            'uri' => 'helpers/routes',
            'created_at' => '2017-11-16 15:54:05',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '14',
            'parent_id' => '8',
            'order' => '10',
            'title' => '業務列表',
            'icon' => 'fa-users',
            'uri' => '/sales',
            'created_at' => '2017-11-19 09:07:16',
            'updated_at' => '2017-12-13 10:05:52',
            ],
            ['id' => '15',
            'parent_id' => '0',
            'order' => '18',
            'title' => '商品管理',
            'icon' => 'fa-archive',
            'uri' => '',
            'created_at' => '2017-11-19 09:09:39',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '16',
            'parent_id' => '15',
            'order' => '19',
            'title' => '商品列表',
            'icon' => 'fa-list-ol',
            'uri' => 'product',
            'created_at' => '2017-11-19 09:10:36',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '17',
            'parent_id' => '61',
            'order' => '21',
            'title' => '商品庫存調整',
            'icon' => 'fa-list-alt',
            'uri' => '',
            'created_at' => '2017-11-19 09:11:40',
            'updated_at' => '2017-12-08 03:05:58',
            ],
            ['id' => '18',
            'parent_id' => '15',
            'order' => '20',
            'title' => '分類管理',
            'icon' => 'fa-tags',
            'uri' => 'product/category',
            'created_at' => '2017-11-19 09:13:00',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '19',
            'parent_id' => '61',
            'order' => '20',
            'title' => '商品退貨',
            'icon' => 'fa-cube',
            'uri' => '',
            'created_at' => '2017-11-19 09:31:27',
            'updated_at' => '2017-12-08 03:14:47',
            ],
            ['id' => '20',
            'parent_id' => '0',
            'order' => '26',
            'title' => '分析報表',
            'icon' => 'fa-bar-chart',
            'uri' => '',
            'created_at' => '2017-11-19 09:36:03',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '21',
            'parent_id' => '0',
            'order' => '14',
            'title' => '業務領退貨',
            'icon' => 'fa-users',
            'uri' => '/',
            'created_at' => '2017-12-08 02:04:42',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '22',
            'parent_id' => '21',
            'order' => '15',
            'title' => '每日配貨',
            'icon' => 'fa-cube',
            'uri' => '/sales/assign',
            'created_at' => '2017-12-08 02:28:48',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '23',
            'parent_id' => '21',
            'order' => '16',
            'title' => '領貨單',
            'icon' => 'fa-cubes',
            'uri' => '/sales/collect',
            'created_at' => '2017-12-08 02:30:24',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '24',
            'parent_id' => '21',
            'order' => '17',
            'title' => '退貨單',
            'icon' => 'fa-cube',
            'uri' => '/sales/refund',
            'created_at' => '2017-12-08 02:32:20',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '25',
            'parent_id' => '8',
            'order' => '12',
            'title' => '業務紀錄檔',
            'icon' => 'fa-file-text-o',
            'uri' => '/sales/log',
            'created_at' => '2017-12-08 02:33:34',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '26',
            'parent_id' => '0',
            'order' => '22',
            'title' => '倉庫管理',
            'icon' => 'fa-university',
            'uri' => 'warehouse',
            'created_at' => '2017-12-08 16:55:15',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '27',
            'parent_id' => '15',
            'order' => '21',
            'title' => '主題系列',
            'icon' => 'fa-asterisk',
            'uri' => 'product/series',
            'created_at' => '2017-12-12 11:04:15',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '28',
            'parent_id' => '29',
            'order' => '25',
            'title' => '店鋪據點',
            'icon' => 'fa-map-marker',
            'uri' => '/web/location',
            'created_at' => '2017-12-11 02:19:12',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '29',
            'parent_id' => '0',
            'order' => '23',
            'title' => '店鋪管理',
            'icon' => 'fa-map-o',
            'uri' => '/',
            'created_at' => '2017-12-12 03:36:47',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '30',
            'parent_id' => '29',
            'order' => '24',
            'title' => '地區',
            'icon' => 'fa-map-signs',
            'uri' => '/web/area',
            'created_at' => '2017-12-12 03:38:03',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '31',
            'parent_id' => '0',
            'order' => '13',
            'title' => '廠商',
            'icon' => 'fa-users',
            'uri' => '',
            'created_at' => '2017-12-13 10:05:02',
            'updated_at' => '2017-12-13 10:07:50',
            ],
            ['id' => '32',
            'parent_id' => '8',
            'order' => '9',
            'title' => '業務主管',
            'icon' => 'fa-user-secret',
            'uri' => 'salse/supervisor',
            'created_at' => '2017-12-13 10:05:46',
            'updated_at' => '2017-12-13 10:06:39',
            ],
            ['id' => '33',
            'parent_id' => '8',
            'order' => '11',
            'title' => '業務公告',
            'icon' => 'fa-file',
            'uri' => 'sales/note',
            'created_at' => '2017-12-13 10:07:09',
            'updated_at' => '2017-12-13 10:07:50',
            ],
        ]);
    }
}
