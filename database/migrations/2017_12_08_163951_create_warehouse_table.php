<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->increments('wid')->comment('倉庫id');
            $table->string('w_name',25)->comment('倉庫名');
            $table->integer('parent_id')->default(0)->comment('父級分類id');
            $table->integer('w_sort')->default(0)->comment('順序');
            $table->string('w_phone',50)->nullable()->comment('連絡電話');
            $table->string('w_address',200)->nullable()->comment('地址');
            $table->text('w_notes')->nullable()->comment('備註');
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
        Schema::dropIfExists('warehouse');
    }
}
