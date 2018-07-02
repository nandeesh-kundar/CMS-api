<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->timestamps();
        });

        
        Schema::create('section_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->enum('type', ['text', 'string', 'file', 'number', 'link']);
            $table->unsignedInteger('section_id');
            $table->timestamps();
            $table->foreign('section_id')
                    ->references('id')
                    ->on('sections')
                    ->onDelete('cascade');
        });
        
        Schema::create('page_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pages_id');
            $table->string('title');
            $table->unsignedInteger('section_id');
            $table->timestamps();
            $table->foreign('pages_id')
                    ->references('id')
                    ->on('pages')
                    ->onDelete('cascade');
            $table->foreign('section_id')
                    ->references('id')
                    ->on('sections')
                    ->onDelete('cascade');
        });
        
        Schema::create('page_section_props', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value')->nullable();
            $table->string('key');
            $table->string('link')->nullable();
            $table->string('type')->nullable();
            $table->unsignedInteger('ps_id');
            $table->unsignedInteger('prop_id');
            $table->timestamps();
            $table->foreign('ps_id')
                    ->references('id')
                    ->on('page_sections')
                    ->onDelete('cascade');
            $table->foreign('prop_id')
                    ->references('id')
                    ->on('section_properties')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('section_properties');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('pages');
    }
}
