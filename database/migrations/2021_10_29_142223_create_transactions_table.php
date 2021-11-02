<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('num_plaque');
            $table->string('date');
            $table->double('prix')->nullable();
            $table->boolean('archive')->default(false);
            $table->bigInteger('agent_id')->unsigned();
            $table->bigInteger('parking_id')->unsigned()->nullable();
            $table->bigInteger('entriprise_id')->unsigned()->nullable();
            $table->bigInteger('client_id')->unsigned();
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
        Schema::dropIfExists('transactions');
    }
}
