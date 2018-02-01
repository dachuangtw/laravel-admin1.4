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
            $table->increments('sadid')->index()->comment('配貨明細id');
            $table->string('assign_id')->comment('配貨單號');
            $table->string('pid')->nullable()->comment('商品編號');
            $table->integer('stid')->unsigned()->index()->comment('庫存id');
            $table->decimal('sad_salesprice',10,2)->default(0.00)->comment('業務單價');  
            $table->integer('sad_quantity')->default(0)->comment('數量');         
            $table->decimal('sad_amount')->default(0.00)->comment('金額(數量*單價)');
            $table->text('sad_notes')->nullable()->comment('備註');
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
