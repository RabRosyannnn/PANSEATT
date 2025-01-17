<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tracking_id'); // Foreign key from reservations
            $table->text('message'); // Message field
            $table->enum('action', ['change', 'cancel']); // Action field
            $table->timestamps();

            // Foreign key constraint (assuming reservations table exists)
            $table->foreign('tracking_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests');
    }
}