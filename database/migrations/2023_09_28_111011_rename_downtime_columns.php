<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->renameColumn('downtime', 'has_downtime');
            $table->renameColumn('downtime_date', 'downtime_start');
            $table->renameColumn('downtime_duration', 'downtime_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->renameColumn('has_downtime', 'downtime');
            $table->renameColumn('downtime_start', 'downtime_date');
            $table->renameColumn('downtime_end', 'downtime_duration');
        });
    }
};
