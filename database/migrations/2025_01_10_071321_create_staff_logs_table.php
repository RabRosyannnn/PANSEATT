<?php

// database/migrations/xxxx_xx_xx_create_staff_logs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffLogsTable extends Migration
{
    public function up()
    {
        Schema::create('staff_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // reference to the staff
            $table->string('action'); // action performed (e.g., added, edited, deleted)
            $table->text('description')->nullable(); // optional description of the action
            $table->timestamps();

            // Foreign key constraint to reference users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_logs');
    }
}
