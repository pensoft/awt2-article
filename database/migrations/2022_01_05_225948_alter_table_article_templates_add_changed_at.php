<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableArticleTemplatesAddChangedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('article_templates', function (Blueprint $table) {
            $table->dateTime('changed_at')->default(\DB::raw('CURRENT_TIMESTAMP'))->index()->after('name');
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

        Schema::table('article_templates', function (Blueprint $table) {
            $table->dropColumn(['changed_at']);
        });
    }
}
