<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_note', function (Blueprint $table) {
            $table->increments('id')->unique()->index()->comment('業務公告id');
            $table->dateTimeTz('note_at')->nullable()->comment('日期');
            $table->string('note_title')->nullable()->comment('標題');
            $table->mediumtext('note_content')->nullable()->comment('內容'); 
            $table->string('note_wid')->nullable()->comment('公告倉庫(字串用|分隔)');
            $table->string('note_target')->nullable()->comment('公告對象(字串用|分隔)');
            $table->string('note_readstatus')->nullable()->comment('公告已讀狀態(字串用|分隔)');
            $table->string('note_status')->nullable()->comment('公告刪除狀態(字串用|分隔)');  
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
        Schema::dropIfExists('sales_note');
    }
}
