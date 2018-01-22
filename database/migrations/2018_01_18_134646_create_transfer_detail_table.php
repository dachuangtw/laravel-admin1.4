<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_detail', function (Blueprint $table) {
            $table->increments('tdid')->comment('調撥單明細id');
            $table->integer('tid')->unsigned()->index()->comment('調撥單id');
            $table->integer('pid')->unsigned()->index()->comment('商品id');
            $table->integer('stid')->unsigned()->index()->comment('庫存id');
            $table->decimal('td_price',10,2)->default(0.00)->comment('單價(業務價)');
            $table->integer('td_quantity')->default(0)->comment('數量');
            $table->decimal('td_amount',10,2)->default(0.00)->comment('總價');
            $table->string('td_notes',100)->nullable()->comment('備註');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_detail');
    }
}
