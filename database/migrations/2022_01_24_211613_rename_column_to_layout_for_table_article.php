<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnToLayoutForTableArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('articles', function (Blueprint $table) {
            $table->renameColumn('article_template_version_id', 'layout_version_id');
            $table->dropForeign(['article_template_id']);
            $table->dropColumn('article_template_id');
            $table->foreignId('layout_id')->after('user_id');


            $table->foreign("layout_id")->references("id")->on("layouts");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            //
        });
    }
}
