<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionsColumnToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->json('permissions');
        });

        DB::table('permissions')->where('id', '=', 1)->update(
            array(
                'permissions' => '["control.console","control.start","control.stop","control.restart","user.create","user.read","user.update","user.delete","file.create","file.read","file.read-content","file.update","file.delete","file.archive","file.sftp","backup.create","backup.read","backup.delete","backup.download","backup.restore","allocation.read","allocation.create","allocation.update","allocation.delete","startup.read","startup.update","startup.docker-image","database.create","database.read","database.update","database.delete","database.view_password","schedule.create","schedule.read","schedule.update","schedule.delete","settings.rename","settings.reinstall","websocket.connect"]'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
}
