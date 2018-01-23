<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer', function (Blueprint $table) {
            $table->increments('tid')->comment('調撥單id');
            $table->string('t_number',50)->index()->comment('調撥單號');
            $table->integer('wid_send')->unsigned()->index()->comment('出貨倉庫id');
            $table->integer('wid_receive')->unsigned()->index()->comment('進貨倉庫id');
            $table->integer('send_user')->unsigned()->index()->comment('調撥人id');
            $table->integer('receive_user')->unsigned()->index()->nullable()->comment('簽收人id');
            $table->decimal('t_amount',10,2)->default(0.00)->comment('總金額');
            $table->string('t_notes',100)->nullable()->comment('備註');
            $table->timestamp('send_at')->nullable()->comment('調撥時間');
            $table->timestamp('receive_at')->nullable()->comment('簽收時間');
            $table->boolean('t_checked')->default(false)->comment('確認簽收');
            $table->integer('update_user')->nullable()->comment('最後更新者id');
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
        Schema::dropIfExists('transfer');
    }
}
