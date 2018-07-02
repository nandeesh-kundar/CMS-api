<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->text('shortDescription')->nullable();
            $table->text('description')->nullable();
            $table->string('featuredImage')->nullable();
            $table->timestamps();
        });


        Schema::create('service_galeries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('image');
            $table->unsignedInteger('services_id');
            $table->foreign('services_id')
                    ->references('id')
                    ->on('services')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_galeries');
        Schema::dropIfExists('services');
    }
}
