<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_course_student_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('enrolled_date');
            $table->enum('status', ['enrolled', 'completed', 'dropped'])->default('enrolled');
            $table->timestamps();
            $table->unique(['course_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_student');
    }
};