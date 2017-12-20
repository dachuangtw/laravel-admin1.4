<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupNumberToProductSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_supplier', function (Blueprint $table) {
            $table->string('sup_number')->unique()->after('supid')->comment('廠商代號');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_supplier', function (Blueprint $table) {
            //
            $table->dropColumn('sup_number');
        });
    }
}
