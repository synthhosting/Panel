<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVsWingsupdaterConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vs_wingsupdater_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('node_id')->nullable();
            $table->string('method');
            $table->text('credential')->nullable();
            $table->text('passphrase')->nullable();
            $table->string('wings_mode')->default('default');
            $table->timestamps();

            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vs_wingsupdater_configurations');
    }
}
