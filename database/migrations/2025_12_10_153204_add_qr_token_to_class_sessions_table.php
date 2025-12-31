<?php
// database/migrations/xxxx_add_qr_token_to_class_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('class_sessions', 'qr_token')) {
                $table->string('qr_token')->nullable()->unique()->after('end_time');
            }
            if (!Schema::hasColumn('class_sessions', 'qr_generated_at')) {
                $table->timestamp('qr_generated_at')->nullable()->after('qr_token');
            }
        });
    }

    public function down()
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'qr_generated_at']);
        });
    }
};