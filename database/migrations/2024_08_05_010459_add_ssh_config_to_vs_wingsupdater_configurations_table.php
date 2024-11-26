<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSshConfigToVsWingsupdaterConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vs_wingsupdater_configurations', function (Blueprint $table) {
            $table->string('ssh_user')->default('root')->after('wings_mode');
            $table->integer('ssh_port')->default(22)->after('ssh_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vs_wingsupdater_configurations', function (Blueprint $table) {
            $table->dropColumn(['ssh_user', 'ssh_port']);
        });
    }
}
