<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->increments('supid')->comment('廠商id');
            $table->string('sup_name',50)->comment('廠商名');
            $table->string('sup_alias',50)->nullable()->comment('廠商簡稱');
            $table->integer('parent_id')->default(0)->comment('父級分類id');
            $table->integer('sup_sort')->default(0)->comment('順序');
            $table->text('sup_notes')->nullable()->comment('備註');
            $table->string('update_user',25)->nullable()->comment('最後更新者');
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
        Schema::dropIfExists('product_supplier');
    }
}
