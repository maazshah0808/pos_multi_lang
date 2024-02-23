<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstColoumInStockdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockdetails', function (Blueprint $table) {
            //
            $table->tinyInteger('gst')->default('0')->comment('0 = > No,1 => Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stockdetails', function (Blueprint $table) {
            //
        });
    }
}
