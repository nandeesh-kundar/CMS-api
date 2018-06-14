<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->enum('linkType', ['custom', 'page']);
            $table->string('pageSlug')->nullable();
            $table->text('customLink')->nullable();
            $table->enum('menuType', ['primary', 'secondary', 'sidebar1', 'sidebar2', 'footer1', 'footer2', 'footer3', 'footer4', 'social']);
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();
            $table->foreign('parent_id')
                    ->references('id')
                    ->on('menus')
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
        Schema::dropIfExists('menus');
    }
}
