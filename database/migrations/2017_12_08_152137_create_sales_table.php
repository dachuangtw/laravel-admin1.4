<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('sid')->unique()->index()->comment('業務id');
            $table->string('wid')->nullable()->comment('倉庫id');
            $table->string('email')->unique()->index()->comment('電子郵件');
            $table->string('password', 60)->comment('密碼');
            $table->string('sales_name', 50)->comment('姓名');
            $table->string('nickname', 50)->nullable()->comment('暱稱');
            $table->string('cellphone', 50)->nullable()->comment('手機號碼');
            $table->timestamp('password_updated_at')->nullable()->comment('密碼更新');
            $table->string('store_location')->nullable()->comment('店鋪據點(字串用|分隔)');
            $table->text('remarks')->nullable()->comment('備註');
            $table->string('client_ip')->nullable()->comment('最近ip');
            $table->text('client_agent')->nullable()->comment('最近使用裝置');
            $table->rememberToken();
            $table->string('confirmation_token')->nullable();
            $table->timestamp('logged_in_at')->nullable()->comment('最近登入日期');
            $table->timestamp('collect_at')->nullable()->comment('最近領貨日期');
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
        Schema::dropIfExists('sales');
    }
}
