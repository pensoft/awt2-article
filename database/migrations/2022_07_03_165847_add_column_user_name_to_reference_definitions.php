<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserNameToReferenceDefinitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('reference_definitions', function (Blueprint $table) {
            $table->string('user_name')->after('user_id');
            $table->string('user_email')->after('user_name');
            $table->uuid('user_id')->change();
        });

        \App\Models\ReferenceDefinition::all()->each(function($ref) {
            $user = \App\Models\User::findOrFail($ref->user_id);
            $ref->user_name = $user->name;
            $ref->user_email = $user->email;
            $ref->save();
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

        Schema::table('reference_definitions', function (Blueprint $table) {
            $table->dropColumn(['user_name', 'user_emails']);
            $table->unsignedInteger("user_id")->change();
            $table->foreign("user_id")->references("id")->on("users");
        });
    }
}
