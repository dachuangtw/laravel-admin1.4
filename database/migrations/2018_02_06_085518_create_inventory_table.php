<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->increments('inid');
            $table->string('in_number',50)->index()->comment('盤點單號');
            $table->integer('wid')->unsigned()->index()->comment('倉庫id');
            $table->timestamp('start_at')->nullable()->comment('盤點開始時間');
            $table->timestamp('finish_at')->nullable()->comment('盤點結束時間');
            $table->integer('update_user')->comment('最後更新者id');
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
        Schema::dropIfExists('inventory');
    }
}
