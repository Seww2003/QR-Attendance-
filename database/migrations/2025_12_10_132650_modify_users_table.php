<?php
// database/migrations/xxxx_xx_xx_xxxxxx_modify_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student'); // admin, lecturer, student
            $table->string('nic')->unique();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nic', 'phone', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};