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
        Schema::connection('mysql2')->create('warehouses', function (Blueprint $table) {
            $table->increments('id')->comment('倉庫id');
            $table->string('name',25)->comment('倉庫名');
            $table->integer('parent_id')->default(0)->comment('父級分類id');
            $table->integer('sort')->default(0)->comment('順序');
            $table->string('phone',50)->nullable()->comment('連絡電話');
            $table->string('address',200)->nullable()->comment('地址');
            $table->text('notes')->nullable()->comment('備註');
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
        Schema::connection('mysql2')->dropIfExists('warehouses');
    }
}
