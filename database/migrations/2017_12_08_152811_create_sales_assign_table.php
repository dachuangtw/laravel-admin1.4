<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_assign', function (Blueprint $table) {
            $table->increments('said')->unique()->index()->comment('配貨id');
            $table->string('assign_id')->unique()->comment('配貨單號');
            $table->date('assign_date')->nullable()->comment('配貨日期');
            //$table->string('pid')->nullable()->comment('商品主檔id');
            //$table->string('p_name')->nullable()->comment('商品名稱');
            //$table->integer('p_salesprice')->default(0)->comment('業務價');
            $table->integer('assign_total')->default(0)->comment('總計金額');
            $table->text('assign_notes')->nullable()->comment('備註');
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
        Schema::dropIfExists('sales_assign');
    }
}
