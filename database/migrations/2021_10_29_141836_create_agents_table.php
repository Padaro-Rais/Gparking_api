<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('nom');
            $table->string('prenoms');
            $table->string('adresse');
            $table->string('telephone');
            $table->string('matricule_ent');
            $table->boolean('status')->default(true);
            $table->boolean('archive')->default(false);
            $table->bigInteger('parking_id')->unsigned();
            $table->bigInteger('entriprise_id')->unsigned();
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
        Schema::dropIfExists('agents');
    }
}
