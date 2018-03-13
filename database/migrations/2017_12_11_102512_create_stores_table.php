<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('stores', function (Blueprint $table) {
            $table->increments('id')->unique()->index()->comment('店鋪據點id');
            $table->string('warehouse_id')->nullable()->comment('倉庫id');
            $table->string('name')->comment('店名');
            $table->integer('city_id')->nullable()->comment('城市id');
            $table->integer('district_id')->nullable()->comment('地區id');
            $table->string('address')->comment('地址');
            $table->integer('rents')->default(0)->comment('租金');
            $table->integer('deposit')->default(0)->comment('押金');
            $table->string('contractor')->nullable()->comment('簽約人');
            $table->dateTimeTz('payment_date')->nullable()->comment('繳款日');
            $table->dateTimeTz('lease_start')->nullable()->comment('租約起始日');
            $table->dateTimeTz('lease_end')->nullable()->comment('租約結束日');
            $table->string('sales')->nullable()->comment('負責業務');
            $table->string('map')->nullable()->comment('地圖');
            $table->string('pic',100)->nullable()->comment('店鋪圖片');
            $table->boolean('showfront')->default(false)->comment('前台顯示');
            $table->string('comment')->nullable()->comment('說明');
            $table->timestamps();
            $table->softDeletes()->comment('軟刪除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('store');
    }
}
