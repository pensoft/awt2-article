<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablesDropForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $foreignKeys = $this->listTableForeignKeys('articles');
            if(in_array('articles_user_id_foreign', $foreignKeys)) {
                $table->dropForeign(['user_id']);
            }
        });

        Schema::table('reference_definitions', function (Blueprint $table) {
            $foreignKeys = $this->listTableForeignKeys('reference_definitions');
            if(in_array('reference_definitions_user_id_foreign', $foreignKeys)) {
                $table->dropForeign(['user_id']);
            }
        });

        Schema::table('reference_items', function (Blueprint $table) {
            $foreignKeys = $this->listTableForeignKeys('reference_items');
            if(in_array('reference_items_user_id_foreign', $foreignKeys)) {
                $table->dropForeign(['user_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    public function listTableForeignKeys($table): array
    {
        $conn = Schema::getConnection()->getDoctrineSchemaManager();

        return array_map(function($key) {
            return $key->getName();
        }, $conn->listTableForeignKeys($table));
    }
}
