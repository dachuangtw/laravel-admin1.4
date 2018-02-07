<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_details', function (Blueprint $table) {
            $table->increments('indid');
            $table->string('in_number',50)->index()->comment('盤點單號');
            $table->integer('pid')->unsigned()->index()->comment('商品id');
            $table->integer('stid')->unsigned()->index()->comment('庫存id');
            $table->string('ind_type',50)->nullable()->comment('款式');
            $table->integer('ind_stock')->default(0)->comment('目前庫存數');
            $table->integer('ind_quantity')->default(0)->comment('盤點數量');
            $table->integer('ind_difference')->default(0)->comment('差異數量');
            $table->string('ind_notes',100)->nullable()->comment('備註');
            $table->integer('ind_user')->nullable()->comment('盤點人員id');
            $table->integer('update_user')->nullable()->comment('最後更新者id');
            $table->timestamp('ind_at')->nullable()->comment('盤點時間');
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
        Schema::dropIfExists('inventory_details');
    }
}
