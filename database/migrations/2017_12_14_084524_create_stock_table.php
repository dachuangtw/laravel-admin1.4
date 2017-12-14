<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->increments('sid')->comment('庫存id');
            $table->integer('pid')->unsigned()->index()->comment('商品主檔id');
            $table->integer('wid')->unsigned()->index()->comment('倉庫id');
            $table->string('s_type',50)->nullable()->comment('款式');
            $table->string('s_barcode',50)->nullable()->comment('條碼');
            $table->integer('s_stock')->default(0)->comment('目前庫存數');
            $table->integer('s_collect')->default(0)->comment('業務可領貨數');
            // $table->integer('s_safestock')->default(0)->comment('安全庫存');
            $table->text('s_notes')->nullable()->comment('備註');
            $table->boolean('showfront')->default(true)->comment('前台顯示');
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
        Schema::dropIfExists('stock');
    }
}
