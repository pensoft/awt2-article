<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableArticleTemplateSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_template_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId("article_template_id");
            $table->foreignId("article_section_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("article_template_id")->references("id")->on("article_templates");
            $table->foreign("article_section_id")->references("id")->on("article_sections");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_template_sections');
    }
}
