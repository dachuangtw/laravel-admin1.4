<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_series', function (Blueprint $table) {
            $table->increments('psid')->comment('主題系列id');
            $table->integer('parent_id')->default(0)->comment('父級分類id');
            $table->string('ps_name',50)->comment('主題系列名稱');
            $table->integer('ps_sort')->default(0)->comment('順序');
            $table->boolean('showfront')->default(false)->comment('前台顯示');
            $table->string('update_user',25)->nullable()->comment('最後更新者');
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
        Schema::dropIfExists('product_series');
    }
}
