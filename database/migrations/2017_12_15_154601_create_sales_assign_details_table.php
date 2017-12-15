<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesAssignDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('sales_assign_details', function (Blueprint $table) {
            $table->increments('said')->unique()->index()->comment('配貨id');
            $table->string('assign_id')->unique()->comment('配貨單號');
            $table->string('pid')->nullable()->comment('產品編號');
            $table->string('s_type',50)->nullable()->comment('款式');
            $table->integer('p_salesprice')->default(0)->comment('業務單價');  
            $table->integer('p_quantity')->default(0)->comment('數量');         
            $table->integer('p_salesprice_total')->default(0)->comment('金額(數量*單價)');
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
        Schema::dropIfExists('sales_assign_details');
    }
}
