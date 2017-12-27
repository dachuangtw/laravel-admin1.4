<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_location', function (Blueprint $table) {
            $table->increments('id')->unique()->index()->comment('店鋪據點id');
            $table->integer('store_sort')->default(0)->comment('順序');
            $table->string('store_name')->comment('店名');
            $table->integer('city_id')->nullable()->comment('城市id');
            $table->integer('district_id')->nullable()->comment('地區id');
            $table->string('store_address')->comment('地址');
            $table->integer('store_rents')->default(0)->comment('租金');
            $table->integer('store_deposit')->default(0)->comment('押金');
            $table->string('store_contractor')->nullable()->comment('簽約人');
            $table->dateTimeTz('store_payment_date')->nullable()->comment('繳款日');
            $table->dateTimeTz('store_lease_start')->nullable()->comment('租約起始日');
            $table->dateTimeTz('store_lease_end')->nullable()->comment('租約結束日');
            $table->string('sales')->nullable()->comment('負責業務');
            $table->string('map')->nullable()->comment('地圖');
            $table->string('store_pic',100)->nullable()->comment('店鋪圖片');
            $table->boolean('showfront')->default(false)->comment('前台顯示');
            $table->string('comment')->nullable()->comment('說明');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_location');
    }
}
