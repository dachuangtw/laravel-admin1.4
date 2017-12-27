<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_receipt', function (Blueprint $table) {
            $table->increments('reid')->comment('進貨單id');
            $table->integer('supid')->unsigned()->index()->comment('廠商id');
            $table->integer('wid')->unsigned()->index()->comment('進貨倉庫id');
            $table->integer('re_user')->unsigned()->index()->comment('進貨人員id');
            $table->decimal('re_amount',10,2)->default(0.00)->comment('總金額'); 
            $table->text('re_notes')->nullable()->comment('備註');
            $table->timestamp('re_delivery')->nullable()->comment('進貨日');
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
        Schema::dropIfExists('product_receipt');
    }
}
