<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->increments('pcid')->comment('商品分類id');
            $table->string('pc_name',50)->nullable()->comment('分類名稱');
            $table->integer('pc_sort')->default(0)->comment('順序');
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
        Schema::dropIfExists('product_category');
    }
}
