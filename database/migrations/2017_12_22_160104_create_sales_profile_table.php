<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesProfileTable extends Migration
{
    public function up()
    {
        Schema::create('sales_profile', function (Blueprint $table) {

            $table->increments('id')->unique();
            $table->integer('sid')->comment('業務id');
            $table->string('email')->nullable()->comment('電子郵件');
            $table->string('nickname', 50)->nullable()->comment('暱稱');
            $table->string('cellphone', 50)->nullable()->comment('手機號碼');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_info');
    }
}
