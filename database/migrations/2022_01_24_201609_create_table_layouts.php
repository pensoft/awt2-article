<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLayouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default(null);
            $table->foreignId("article_template_id");
            $table->integer('article_template_version_id')->nullable(true);
            $table->text('schema_settings');
            $table->json('rules');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("article_template_id")->references("id")->on("article_templates");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layouts');
    }
}
