<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_category', function (Blueprint $table) {
            $table->increments('scid')->comment('庫存分類id');
            $table->string('sc_number',50)->comment('分類代號');
            $table->string('sc_name',50)->comment('分類名');
            $table->integer('parent_id')->default(0)->comment('父級分類id');
            $table->integer('sc_sort')->default(0)->comment('順序');
            $table->text('sc_notes')->nullable()->comment('備註');
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
        Schema::dropIfExists('stock_category');
    }
}
