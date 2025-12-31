<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_attendances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
           $table->foreignId('session_id')->constrained('class_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->timestamp('attended_at');
            $table->string('status')->default('present'); // present, late, absent
            $table->text('remarks')->nullable();
            $table->unique(['session_id', 'student_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};