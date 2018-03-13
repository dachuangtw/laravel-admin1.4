<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorkDateToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('sales', function (Blueprint $table) {
            $table->string('start_work_date')->nullable()->after('resign');
            $table->string('end_work_date')->nullable()->after('start_work_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('sales', function (Blueprint $table) {
            $table->dropColumn('start_work_date');
            $table->dropColumn('end_work_date');
        });
    }
}
