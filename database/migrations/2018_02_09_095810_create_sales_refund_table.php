<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_refund', function (Blueprint $table) {
            $table->increments('srid')->index()->comment('退貨id');
            $table->string('refund_id')->unique()->comment('退貨單號');
            $table->string('collect_id')->nullable()->comment('領貨單號');
            $table->integer('wid')->unsigned()->index()->comment('倉庫wid');
            $table->integer('sales_id')->comment('業務id');
            $table->date('refund_date')->nullable()->comment('退貨日期');
            $table->decimal('refund_amount')->default(0.00)->comment('總計金額');
            $table->boolean('refund_method')->default(0)->comment('退貨方式');        
            $table->boolean('refundgoods_check')->default(0)->comment('退貨確認');
            $table->string('refundgoods_check_user')->nullable()->comment('確認退貨人');
            $table->boolean('refund_check')->default(0)->comment('退款確認');
            $table->string('refund_check_user')->nullable()->comment('確認退款人');           
            $table->text('refund_notes')->nullable()->comment('備註');
            $table->string('update_user',25)->nullable()->comment('最後更新者');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_refund');
    }
}
