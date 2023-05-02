<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ArticleSections;

class AddColumnComplexSectionVersionIdToArticleComplexSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_complex_sections', function (Blueprint $table) {
            $table->integer('complex_section_version_id')->nullable(true)->after('version_id');
        });

        ArticleSections::onlyComplex()->get()->each(function(ArticleSections $section) {
            $version_id = $section->latestVersion->id;
            $section->sections()->update(['complex_section_version_id' => $version_id]);
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
            $table->dropColumn(['complex_section_version_id']);
        });
    }
}
