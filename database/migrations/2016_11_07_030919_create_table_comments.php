<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('question_id')->nullable();
            $table->unsignedInteger('answer_id')->nullable();
            $table->unsignedInteger('reply_to')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('question_id')->references('id')->on('question');
            $table->foreign('answer_id')->references('id')->on('answer');
            $table->foreign('reply_to')->references('id')->on('comments');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments');
    }
}
