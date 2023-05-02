<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSettingsToArticleSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_complex_sections', function (Blueprint $table) {
            $table->dropColumn(['settings']);
        });

        Schema::table('article_sections', function (Blueprint $table) {
            $table->json('complex_section_settings')->nullable(true)->after('compatibility');
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
            $table->json('settings')->default('{}')->after('order');
        });

        Schema::table('article_sections', function (Blueprint $table) {
            $table->dropColumn(['complex_section_settings']);
        });
    }
}
