<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReferenceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_items', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->json('data');
            $table->unsignedBigInteger("reference_definition_id");
            $table->unsignedInteger("user_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("reference_definition_id")->references("id")->on("reference_definitions");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reference_items');
    }
}
