
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('busydays', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->timestamps();
            
            // Prevent duplicate dates
            $table->unique('date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('busy_days');
    }
};