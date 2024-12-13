<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HotelsTable extends Migration
{
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->bigIncrements('hotel_id');
            $table->string('name');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('location_id')->on('locations')->onDelete('cascade');
            $table->decimal('rating', 3, 2)->nullable()->default(null);
            $table->decimal('price_per_night', 15, 2);
            $table->json('amenities')->nullable();
            $table->boolean('availability')->default(true);
            $table->softDeletes();
            $table->timestamps();


            $table->index('city_id');
            $table->index('availability');
        });
    }

    public function down()
    {Schema::dropIfExists('hotels');}
}