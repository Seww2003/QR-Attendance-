<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_courses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('course_code')->unique();
        $table->string('course_name'); // Change this to 'name' if needed
        // OR keep as 'course_name' but make sure it's not null
        $table->text('description')->nullable();
        $table->foreignId('lecturer_id')->nullable()->constrained()->onDelete('set null');
        $table->integer('credit_hours')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}
};