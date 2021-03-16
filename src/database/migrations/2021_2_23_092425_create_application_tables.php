<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_application_apps', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('character_name');
            $table->integer('application_id');
            $table->string('responses');
            $table->string('notes');
            $table->integer('status');
            $table->string('approver');
            $table->timestamps();
            $table->primary(['user_id', 'application_id']);
        });
        Schema::create('seat_application_instructions', function (Blueprint $table) {
            $table->string('instructions');
        });
        Schema::create('seat_application_questions', function (Blueprint $table) {
            $table->integer('qid');
            $table->integer('order');
            $table->string('question');
            $table->string('type');
            $table->string('options');
            $table->string('required');
            $table->primary(['qid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_application_apps');
    }
}
