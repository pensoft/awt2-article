<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnCompatibilityUpdateTableArticleSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_sections', function (Blueprint $table) {
            $table->json('compatibility')->nullable(true)->after('template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_sections', function (Blueprint $table) {
            $table->dropColumn(['compatibility']);
        });
    }
}
