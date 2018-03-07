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
      //MENU
      $this->call(AdminMenuTableSeeder::class);
      //WAREHOUSE
      $this->call(WarehousesTableSeeder::class);
      //WEBAREA
      $this->call(TwAreasTableSeeder::class);
      //ROLES
      $this->call(AdminRolesTableSeeder::class);
      //PERMISSIONS
      $this->call(AdminPermissionsTableSeeder::class);
      //ROLES and PERMISSIONS
      $this->call(AdminRolePermissionsTableSeeder::class);
    }
}
