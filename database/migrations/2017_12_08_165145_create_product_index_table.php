<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_index', function (Blueprint $table) {
            $table->increments('pid')->comment('商品主檔id');            
            $table->string('p_name',50)->nullable()->comment('商品名稱');
            $table->string('p_pic',100)->nullable()->comment('商品主圖');
            $table->text('p_images')->nullable()->comment('商品副圖(用|分隔)');
            $table->text('p_description')->nullable()->comment('商品說明');
            $table->string('p_number',25)->nullable()->comment('商品編號');
            $table->integer('p_price')->default(0)->comment('定價'); 
            $table->integer('p_retailprice')->default(0)->comment('售價'); 
            $table->integer('p_specialprice')->default(0)->comment('優惠價');
            $table->integer('p_salesprice')->default(0)->comment('業務價');
            $table->integer('p_staffprice')->default(0)->comment('員工價');
            $table->integer('p_costprice')->default(0)->comment('進價');
            $table->text('p_category')->nullable()->comment('商品分類勾選(用|分隔)'); 
            $table->text('p_series')->nullable()->comment('主題系列勾選(用|分隔)');
            $table->text('p_notes')->nullable()->comment('備註');
            $table->boolean('showfront')->default(true)->comment('前台顯示');
            $table->boolean('shownew')->default(true)->comment('新荷入庫顯示');
            $table->string('update_user',25)->nullable()->comment('最後更新者');
            $table->timestamps();
            $table->softDeletes();              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_index');
    }
}
