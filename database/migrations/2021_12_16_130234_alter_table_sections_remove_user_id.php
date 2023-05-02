<?php

use App\Enums\ArticleSectionTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSectionsRemoveUserId extends Migration
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
            $table->dropColumn(['user_id']);
            $table->integer('type')->default(ArticleSectionTypes::SIMPLE)->after('template');
            $table->json('schema')->nullable(true)->change();
            $table->renameColumn('title', 'name');
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
            $table->bigInteger('user_id')->unsigned()->index();
            $table->dropColumn(['type']);
            $table->json('schema')->nullable(false)->change();
            $table->renameColumn('name', 'title');
        });
    }
}
