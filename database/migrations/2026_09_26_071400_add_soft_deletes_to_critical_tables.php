<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // القضايا
        Schema::table('cases', function (Blueprint $table) {
            $table->softDeletes();
        });

        // العقود
        Schema::table('contracts', function (Blueprint $table) {
            $table->softDeletes();
        });

        // الموكلون
        Schema::table('clients', function (Blueprint $table) {
            $table->softDeletes();
        });

        // المستخدمون
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        // الجلسات
        Schema::table('case_sessions', function (Blueprint $table) {
            $table->softDeletes();
        });

        // المواعيد
        Schema::table('appointments', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('cases', fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('contracts', fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('clients', fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('users', fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('case_sessions', fn (Blueprint $t) => $t->dropSoftDeletes());
        Schema::table('appointments', fn (Blueprint $t) => $t->dropSoftDeletes());
    }
};