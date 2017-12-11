<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->increments('wid')->comment('倉庫id');
            $table->string('w_name',25)->comment('倉庫名');
            $table->string('w_phone',50)->nullable()->comment('連絡電話');
            $table->string('w_postcode',25)->nullable()->comment('郵遞區號');
            $table->string('w_city',25)->nullable()->comment('縣市');
            $table->string('w_area',25)->nullable()->comment('鄉鎮市區');
            $table->string('w_street',100)->nullable()->comment('街道地址');
            $table->text('w_notes')->nullable()->comment('備註');
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
        Schema::dropIfExists('warehouse');
    }
}
