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
            $table->string('q1');
            $table->string('q2');
            $table->string('q3');
            $table->string('q4');
            $table->string('q5');
            $table->string('q6');
            $table->string('q7');
            $table->string('q8');
            $table->string('q9');
            $table->string('q10');
            $table->string('q11');
            $table->string('q12');
            $table->string('q13');
            $table->string('q14');
            $table->string('q15');
            $table->string('q16');
            $table->integer('approved');
            $table->string('approver');
            $table->timestamps();
            $table->primary(['user_id', 'application_id']);
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
