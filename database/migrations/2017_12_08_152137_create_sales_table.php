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
        Schema::connection('mysql2')->create('sales', function (Blueprint $table) {
            $table->increments('id')->unique()->index()->comment('業務id');
            $table->string('warehouse_id')->nullable()->comment('倉庫id');
            $table->string('account')->nullable()->comment('電子郵件');
            $table->string('password', 60)->comment('密碼');
            $table->string('name', 50)->comment('姓名');
            $table->string('nickname', 50)->nullable()->comment('暱稱');
            $table->string('cellphone', 50)->nullable()->comment('手機號碼');
            $table->timestamp('password_updated_at')->nullable()->comment('密碼更新');
            $table->text('remarks')->nullable()->comment('備註');
            $table->string('client_ip')->nullable()->comment('最近ip');
            $table->text('client_agent')->nullable()->comment('最近使用裝置');
            $table->rememberToken();
            $table->string('confirmation_token')->nullable();
            $table->timestamp('logged_in_at')->nullable()->comment('最近登入日期');
            $table->timestamp('collect_at')->nullable()->comment('最近領貨日期');
            $table->timestamps();
            $table->softDeletes()->comment('軟刪除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('sales');
    }
}
