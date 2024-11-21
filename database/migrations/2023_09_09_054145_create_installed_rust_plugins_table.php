<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installed_rust_plugins', function (Blueprint $table) {
            $table->id();
            $table->integer('server_id')->unsigned();
            $table->string('url');
            $table->string('title');
            $table->string('name');
            $table->string('tags_all')->nullable();
            $table->string('icon_url')->nullable();
            $table->string('author')->nullable();
            $table->string('downloads_shortened');
            $table->string('donate_url')->nullable();
            $table->string('version');
            $table->boolean('update');
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
        Schema::dropIfExists('installed_rust_plugins');
    }
};