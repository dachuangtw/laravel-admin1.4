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
            $table->string('p_number',25)->nullable()->comment('商品編號');
            $table->string('p_name',50)->nullable()->comment('商品名稱');
            $table->string('p_pic',100)->nullable()->comment('商品主圖');
            $table->text('p_images')->nullable()->comment('商品副圖(用|分隔)');
            $table->text('p_description')->nullable()->comment('商品說明');
            $table->string('p_unit',5)->default('個')->comment('單位');
            $table->decimal('p_price',10,2)->default(0.00)->comment('定價'); 
            $table->decimal('p_retailprice',10,2)->default(0.00)->comment('售價'); 
            $table->decimal('p_salesprice',10,2)->default(0.00)->comment('業務價');
            $table->decimal('p_costprice',10,2)->default(0.00)->comment('成本價');
            $table->text('p_category')->nullable()->comment('商品分類勾選(用|分隔)'); 
            $table->text('p_series')->nullable()->comment('主題系列勾選(用|分隔)');
            $table->text('p_notes')->nullable()->comment('業務備註');
            $table->boolean('showfront')->default(false)->comment('前台顯示');
            $table->boolean('shownew')->default(false)->comment('新荷入庫顯示');
            $table->boolean('showsales')->default(false)->comment('業務可領貨顯示');
            $table->string('update_user',25)->nullable()->comment('最後更新者');
            $table->timestamp('last_delivery')->nullable()->comment('最後進貨日');
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
