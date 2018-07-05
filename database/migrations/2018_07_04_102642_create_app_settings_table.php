<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('companyName')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('primaryPhone')->nullable();
            $table->string('secondaryPhone')->nullable();
            $table->string('primaryEmail')->nullable();
            $table->string('secondaryEmail')->nullable();
            $table->string('primaryAddress')->nullable();
            $table->string('secondaryAddress')->nullable();
            $table->string('facebookLink')->nullable();
            $table->string('twitterLink')->nullable();
            $table->string('instaLink')->nullable();
            $table->string('googleLink')->nullable();
            $table->string('whatsAppLink')->nullable();
            $table->string('youtubeLink')->nullable();
            $table->string('footerMessage')->nullable();
            $table->text('embedMap')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
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
        Schema::dropIfExists('app_settings');
    }
}
