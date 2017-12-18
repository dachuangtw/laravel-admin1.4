<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWidToAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //管理者增加倉庫id欄位
        Schema::table('admin_users', function (Blueprint $table) {
            $table->integer('wid')->default(0)->after('id')->comment('倉庫id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            //
            $table->dropColumn('wid');
        });
    }
}
