<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesCollectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_collect_details', function (Blueprint $table) {
            $table->increments('scdid')->index()->comment('領貨id');
            $table->string('collect_id')->index()->comment('領貨單號');
            $table->integer('pid')->unsigned()->index()->comment('商品編號');
            $table->integer('stid')->unsigned()->index()->comment('庫存id');
            $table->decimal('scd_salesprice',10,2)->default(0.00)->comment('單價(業務價)');
            $table->integer('scd_quantity')->default(0)->comment('數量');
            $table->decimal('scd_amount')->default(0.00)->comment('總計金額');    
            $table->boolean('scd_check')->nullable()->comment('點貨確認');      
            $table->text('scd_notes')->nullable()->comment('商品備註');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_collect_details');
    }
}
