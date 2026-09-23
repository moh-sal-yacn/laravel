<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_sessions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('session_date');
            $table->text('session_notes')->nullable();
            $table->date('next_session_date')->nullable();
            $table->foreignId('cases_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_sessions');
    }
};
