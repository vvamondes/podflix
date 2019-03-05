<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramsEpisodesSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('slug')->unique();
            $table->integer('count')->default(0);
            $table->timestamps();
        });

        // Create table
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('feed')->nullable();
            $table->boolean('activated')->default(true);
            $table->integer('subscribed_count')->default(0);
            $table->integer('episodes_count')->default(0);
            $table->dateTime('checked_at')->default(date("Y-m-d H:i:s"));
            $table->timestamps();
        });


        // Create table
        Schema::create('episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('slug')->unique();

            $table->string('file_url');
            $table->string('file_type')->nullable();
            $table->string('file_length')->nullable();

            $table->string('duration')->nullable();

            $table->float('played_count')->default(0);
            $table->integer('liked_count')->default(0);
            $table->integer('disliked_count')->default(0);

            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['title', 'program_id']);
        });


        // Create table
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('slug')->unique();
            $table->integer('count')->default(0);
            $table->timestamps();
        });


        // Create table
        Schema::create('catalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });





        //(Many-to-Many)

        // Create table for associating (Many-to-Many)
        Schema::create('category_program', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('program_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['program_id', 'category_id']);
        });


        // Create table for associating (Many-to-Many)
        Schema::create('episode_tag', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('episode_id')->unsigned();
            $table->integer('tag_id')->unsigned();

            $table->foreign('episode_id')->references('id')->on('episodes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['episode_id', 'tag_id']);
        });




        // Create table for associating (Many-to-Many)
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('program_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['program_id', 'user_id']);
        });

        // Create table for associating (Many-to-Many)
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('episode_id')->unsigned();
            $table->foreign('episode_id')->references('id')->on('episodes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('user_id')->nullable();
            $table->integer('event_id')->default(1);

            $table->string('ip')->nullable();

            $table->timestamps();

        });

        // Create table for associating (Many-to-Many)
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('episode_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('episode_id')->references('id')->on('episodes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->boolean('liked');
            $table->timestamps();

            $table->unique(['episode_id', 'user_id']);
        });

        // Create table for associating (Many-to-Many)
        Schema::create('playlists', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('episode_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->foreign('episode_id')->references('id')->on('episodes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['episode_id', 'user_id']);
        });


        // Create table for associating (Many-to-Many)
        Schema::create('catalog_category', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('catalog_id')->unsigned();
            $table->integer('category_id')->unsigned();

            $table->foreign('catalog_id')->references('id')->on('catalogs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();

            $table->unique(['catalog_id', 'category_id']);
        });










        //(One-to-Many)

        // Create table one to many
        Schema::create('episode_twitters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('episode_id')->unsigned();
            $table->foreign('episode_id')->references('id')->on('episodes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->timestamps();

        });




        // Create table one to many
        Schema::create('program_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('small')->nullable();
            $table->string('medium')->nullable();
            $table->string('large')->nullable();

            $table->timestamps();

        });

        // Create table one to many
        Schema::create('program_facebooks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->nullable();

            $table->timestamps();

        });

        // Create table one to many
        Schema::create('program_twitters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->nullable();

            $table->timestamps();

        });

        // Create table one to many
        Schema::create('program_googlepluses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->nullable();

            $table->timestamps();

        });

        // Create table one to many
        Schema::create('program_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->nullable();

            $table->timestamps();

        });

        // Create table one to many
        Schema::create('program_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('name')->nullable();

            $table->timestamps();

        });

        // Create table one to many
        Schema::create('episode_autoposts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('episode_id')->unsigned();
            $table->foreign('episode_id')->references('id')->on('episodes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('social')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        //(One-to-Many)
        Schema::dropIfExists('episode_autoposts');
        Schema::dropIfExists('episode_twitters');

        Schema::dropIfExists('program_images');
        Schema::dropIfExists('program_twitters');
        Schema::dropIfExists('program_facebooks');
        Schema::dropIfExists('program_googleplus');
        Schema::dropIfExists('program_emails');
        Schema::dropIfExists('program_sites');


        //(Many-to-Many)
        Schema::dropIfExists('playlists');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('players');
        Schema::dropIfExists('subscriptions');


        Schema::dropIfExists('episode_tag');
        Schema::dropIfExists('category_program');
        Schema::dropIfExists('catalog_category');


        //(Tables)
        Schema::dropIfExists('catalogs');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('episodes');
        Schema::dropIfExists('programs');
    }
}
