<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('tw_areas', function (Blueprint $table) {
            $table->increments('id')->comment('地區id');
            $table->integer('parent_id')->comment('父級ID');
            $table->integer('sort')->comment('順序');
            $table->boolean('type');
            $table->string('name')->comment('地區名稱');
            $table->string('zipcode')->comment('郵遞區號');
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
        Schema::connection('mysql2')->dropIfExists('tw_areas');
    }
}
