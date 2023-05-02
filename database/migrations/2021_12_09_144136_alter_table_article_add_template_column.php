<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableArticleAddTemplateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('article_sections', function (Blueprint $table) {
            $table->renameColumn('data', 'schema');
            $table->longText('template')->default('')->after('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('article_sections', function (Blueprint $table) {
            $table->renameColumn('schema', 'data');
            $table->dropColumn(['template']);
        });
    }
}
