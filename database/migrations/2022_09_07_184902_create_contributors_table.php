<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ArticleCollaboratorTypes;

class CreateContributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_collaborators', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->uuid('article_id');
            $table->string('type')->default(ArticleCollaboratorTypes::READER);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_collaborators');
    }
}
