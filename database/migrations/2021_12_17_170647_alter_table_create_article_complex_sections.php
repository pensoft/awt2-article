<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCreateArticleComplexSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_complex_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId("article_section_id");
            $table->foreignId("article_simple_section_id");
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("article_section_id")->references("id")->on("article_sections");
            $table->foreign("article_simple_section_id")->references("id")->on("article_sections");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_complex_sections');
    }
}
