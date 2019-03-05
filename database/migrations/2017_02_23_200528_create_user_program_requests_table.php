<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProgramRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_program_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->dateTime('replied_at')->nullable();

            $table->integer('program_id')->nullable()->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->enum('request', ['create', 'update', 'delete']);
            $table->enum('status', ['open','in progress','done']);

            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('feed')->nullable();

            $table->string('logo')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('googleplus')->nullable();
            $table->string('site')->nullable();
            $table->string('email')->nullable();

            $table->text('other')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_program_requests');
    }
}
