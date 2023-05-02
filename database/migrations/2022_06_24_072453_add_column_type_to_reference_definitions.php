<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTypeToReferenceDefinitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference_definitions', function (Blueprint $table) {
            $table->string('type', 255)->after('title');
            $table->renameColumn('template', 'settings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reference_definitions', function (Blueprint $table) {
            $table->dropColumn(['type']);
            $table->renameColumn('settings', 'template');
        });
    }
}
