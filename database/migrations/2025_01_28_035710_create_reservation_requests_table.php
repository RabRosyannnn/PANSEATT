<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_id');
            $table->enum('action', ['change', 'cancel']);
            $table->text('message');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->timestamps();
            
            // Foreign key if you have a reservations table
            // $table->foreign('tracking_id')->references('tracking_id')->on('reservations');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservation_requests');
    }
};