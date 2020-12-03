<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('command_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('command_id');
            $table->unsignedBigInteger('stock_id');
            $table->float('price')->comment('по какой цене');
            $table->integer('count')->comment('кол-во акций. если < 0 - была продажа, если > 0 - была покупка');
            $table->timestamp('time_by_exchange');
            $table->timestamps();

            $table->foreign('command_id')->references('id')->on('commands')->onDelete('cascade')->onDelete('cascade');
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('command_stock');
    }
}
