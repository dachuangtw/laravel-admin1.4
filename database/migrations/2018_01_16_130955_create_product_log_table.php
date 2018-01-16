<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_log', function (Blueprint $table) {

            $table->integer('pid')->unsigned()->index()->comment('商品主檔id');
            $table->decimal('pl_price1',10,2)->default(0.00)->comment('原始成本價');
            $table->decimal('pl_price2',10,2)->default(0.00)->comment('新成本價');
            $table->string('pl_notes',100)->nullable()->comment('變更事由');
            $table->string('update_user',25)->nullable()->comment('變更人');
            $table->timestamp('update_at')->nullable()->comment('變更時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_log');
    }
}
