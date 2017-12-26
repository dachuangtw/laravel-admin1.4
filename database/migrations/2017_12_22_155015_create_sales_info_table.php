<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_info', function (Blueprint $table) {

            $table->increments('id')->unique();
            $table->integer('sid')->comment('業務id');
            $table->string('wid')->nullable()->comment('倉庫id');
            $table->string('resign',1)->default('f')->comment('離職');
            $table->string('start_work_date')->nullable()->comment('到職日');
            $table->string('end_work_date')->nullable()->comment('離職日');
            $table->string('store_location')->nullable()->comment('店鋪據點(字串用|分隔)');
            $table->text('remarks')->nullable()->comment('備註');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_info');
    }
}
