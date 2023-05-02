<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSettingsToArticleComplexSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_complex_sections', function (Blueprint $table) {
            $table->json('settings')->default('{}')->after('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_complex_sections', function (Blueprint $table) {
            $table->dropColumn(['settings']);
        });
    }
}
