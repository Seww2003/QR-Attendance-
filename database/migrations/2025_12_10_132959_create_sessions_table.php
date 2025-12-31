<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // CHANGE THIS LINE: Use a different table name
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('topic')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('qr_token')->unique()->nullable();
            $table->timestamp('qr_generated_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        // CHANGE THIS TOO
        Schema::dropIfExists('class_sessions');
    }
};