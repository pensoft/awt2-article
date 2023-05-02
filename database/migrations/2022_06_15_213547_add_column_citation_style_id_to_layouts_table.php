<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCitationStyleIdToLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->foreignId("citation_style_id")->nullable(true)->after('article_template_version_id');

            $table->foreign("citation_style_id")->references("id")->on("citation_styles");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('layouts', function (Blueprint $table) {
            $table->dropForeign(['citation_style_id']);
            $table->dropColumn('citation_style_id');
        });
    }
}
