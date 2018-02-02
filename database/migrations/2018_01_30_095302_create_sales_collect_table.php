<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesCollectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_collect', function (Blueprint $table) {
            $table->increments('scid')->index()->comment('領貨id');
            $table->string('collect_id')->unique()->comment('領貨單號');
            $table->integer('wid')->unsigned()->index()->comment('倉庫wid');
            $table->integer('sales_id')->comment('業務id');
            $table->date('collect_date')->nullable()->comment('領貨日期');
            $table->decimal('collect_amount')->default(0.00)->comment('總計金額');        
            $table->boolean('collect_check')->default(0)->comment('領貨確認');
            $table->string('collect_check_user')->nullable()->comment('確認領貨人');
            $table->boolean('receipt_check')->default(0)->comment('收款確認');
            $table->string('receipt_check_user')->nullable()->comment('確認收款人');           
            $table->text('collect_notes')->nullable()->comment('備註');
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
        Schema::dropIfExists('sales_collect');
    }
}
