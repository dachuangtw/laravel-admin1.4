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
            $table->string('store_area')->comment('地區');
            $table->string('store_name')->comment('店名');
            $table->string('store_address')->comment('地址');
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
