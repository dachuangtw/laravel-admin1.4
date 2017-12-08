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
            $table->date('assign_date')->nullable()->comment('配貨日期');
            $table->string('pdid')->nullable()->comment('商品副檔id');
            $table->string('p_name')->nullable()->comment('商品名稱');
            $table->integer('p_salesprice')->default(0)->comment('業務價');
            $table->integer('p_salesprice_total')->default(0)->comment('業務總價');
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
