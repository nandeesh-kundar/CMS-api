<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('typeName')->unique();
            $table->timestamps();
        });


        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('bannerimg')->nullable();
            $table->timestamps();
        });

        Schema::table('banners', function($table) {
            $table->unsignedInteger('banner_types_id');
            $table->foreign('banner_types_id')
                    ->references('id')
                    ->on('banner_types')
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
        Schema::dropIfExists('banners');
        Schema::dropIfExists('banner_types');
    }
}
