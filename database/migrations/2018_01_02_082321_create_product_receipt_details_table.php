<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReceiptDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_receipt_details', function (Blueprint $table) {
            $table->increments('redid')->comment('進貨單明細id');
            $table->string('re_number',50)->comment('進貨單號');
            $table->integer('pid')->unsigned()->index()->comment('商品主檔id');
            $table->integer('red_quantity')->default(0)->comment('數量');
            $table->decimal('red_price',10,2)->default(0.00)->comment('單價'); 
            $table->decimal('red_amount',10,2)->default(0.00)->comment('總金額'); 
            $table->string('red_notes',100)->nullable()->comment('備註');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_receipt_details');
    }
}
