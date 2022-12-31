<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('location_id')->unsigned();
            $table->string('title');
            $table->string('position');
            $table->text('description');
            $table->integer('experience_years')->unsigned();
            $table->string('highlight')->nullable();
            $table->string('keystrength')->nullable();
            $table->integer('views')->default(1)->unsigned();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('image')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_premium')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
