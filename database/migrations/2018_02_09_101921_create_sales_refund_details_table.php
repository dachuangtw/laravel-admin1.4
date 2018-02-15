<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRefundDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_refund_details', function (Blueprint $table) {
            $table->increments('srdid')->index()->comment('退貨id');
            $table->string('refund_id')->index()->comment('退貨單號');
            $table->integer('pid')->unsigned()->index()->comment('商品編號');
            $table->integer('stid')->unsigned()->index()->comment('庫存id');
            $table->decimal('srd_salesprice',10,2)->default(0.00)->comment('單價(業務價)');
            $table->integer('srd_quantity')->default(0)->comment('數量');
            $table->integer('srd_discount')->default(100.00)->comment('退貨折數');
            $table->decimal('srd_amount')->default(0.00)->comment('總計金額');    
            $table->boolean('srd_check')->nullable()->comment('點貨確認');      
            $table->text('srd_notes')->nullable()->comment('商品備註');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_refund_details');

    }
}
