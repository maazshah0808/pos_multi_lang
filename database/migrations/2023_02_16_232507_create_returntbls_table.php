<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturntblsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returntbls', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 20, 2);
            $table->integer('total_qty');
            $table->unsignedBigInteger('invoice_id');
            $table->text('fbr_invoice_no')->nullable();
            $table->text('detail')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returntbls');
    }
}
