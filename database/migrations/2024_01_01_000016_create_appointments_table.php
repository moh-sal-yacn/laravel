<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('appointment_date');
            $table->enum('status', ['مجدول', 'مكتمل', 'ملغي'])->default('مجدول');
            $table->foreignId('bookings_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clients_id')->constrained('clients')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
