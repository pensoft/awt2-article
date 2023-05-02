<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('roles');

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role');
            $table->string('name');
            $table->timestamps();
        });

        $roles = [
            [
                'role'        => 'admin',
                'name' => 'Administrator',
            ],
            [
                'role'        => 'author',
                'name' => 'Author',
            ],
            [
                'role'        => 'editor',
                'name' => 'Editor',
            ],
        ];
        foreach ($roles as $role) {
            \DB::table('roles')->insert(
                [
                    'role' => $role['role'],
                    'name' => $role['name'],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('roles');
    }
}
