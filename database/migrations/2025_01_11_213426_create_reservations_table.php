<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('contact_information');
            $table->date('date');
            $table->time('time');
            $table->integer('number_of_guests');
            $table->boolean('booking_confirmation')->default(false);
            $table->decimal('deposit', 8, 2)->nullable();
            $table->string('occasion')->nullable();
            $table->string('bundle')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}