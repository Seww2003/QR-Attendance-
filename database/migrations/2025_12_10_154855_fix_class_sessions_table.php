<?php
// database/migrations/xxxx_fix_class_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, check if table exists
        if (!Schema::hasTable('class_sessions')) {
            // Create table if doesn't exist
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
        } else {
            // Fix existing table
            Schema::table('class_sessions', function (Blueprint $table) {
                // Make sure topic column exists
                if (!Schema::hasColumn('class_sessions', 'topic')) {
                    $table->string('topic')->nullable()->after('course_id');
                }
                
                // Make sure other columns exist
                $columns = ['date', 'start_time', 'end_time', 'qr_token', 'qr_generated_at', 'is_active'];
                foreach ($columns as $column) {
                    if (!Schema::hasColumn('class_sessions', $column)) {
                        if ($column === 'date') {
                            $table->date('date')->nullable();
                        } elseif ($column === 'start_time' || $column === 'end_time') {
                            $table->time($column)->nullable();
                        } elseif ($column === 'qr_token') {
                            $table->string('qr_token')->unique()->nullable();
                        } elseif ($column === 'qr_generated_at') {
                            $table->timestamp('qr_generated_at')->nullable();
                        } elseif ($column === 'is_active') {
                            $table->boolean('is_active')->default(true);
                        }
                    }
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('class_sessions');
    }
};