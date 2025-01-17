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
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('number_of_guests');
            $table->string('booking_confirmation')->default('processing'); // Updated column
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('deposit', 8, 2)->nullable();
            $table->string('occasion')->nullable();
            $table->string('note')->nullable();
            $table->string('bundle')->nullable();
            $table->string('tracking_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
