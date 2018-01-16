<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_log', function (Blueprint $table) {
            
            $table->integer('pid')->unsigned()->index()->comment('商品主檔id');
            $table->integer('wid')->unsigned()->index()->comment('倉庫id');
            $table->integer('stid')->unsigned()->index()->comment('庫存id');
            $table->char('sl_calc',1)->nullable()->comment('增+/減-');
            $table->integer('sl_quantity')->default(0)->comment('變更數量');
            $table->integer('sl_stock')->default(0)->comment('變更後庫存');
            $table->string('sl_notes',100)->nullable()->comment('變更事由');
            $table->string('update_user',25)->nullable()->comment('變更人');
            $table->timestamp('update_at')->nullable()->comment('變更時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_log');
    }
}
