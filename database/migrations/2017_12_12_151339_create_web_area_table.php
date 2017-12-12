<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_area', function (Blueprint $table) {
            $table->increments('id')->unique()->index()->comment('地區id');
            $table->string('area_name')->comment('地區名稱');
            $table->integer('area_sort')->default(0)->comment('順序');
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
        Schema::dropIfExists('web_area');
    }
}
