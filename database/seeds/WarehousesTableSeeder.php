<?php

use Illuminate\Database\Seeder;

class WarehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::connection('mysql2')->table('warehouses')->delete();

        DB::connection('mysql2')->table('warehouses')->insert([
            [
	            'name'   => '新竹倉',
			],
            [
	            'name'   => '台中倉',
			],
            [
	            'name'   => '雲林倉',
			],
            [
	            'name'   => '高雄倉',
			],
        ]);
    }
}
