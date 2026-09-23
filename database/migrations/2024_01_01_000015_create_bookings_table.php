<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_name', 45);
            $table->enum('booking_type', ['استشارة', 'متابعة قضية', 'توقيع عقد', 'أخرى'])->default('استشارة');
            $table->dateTime('preferred_date');
            $table->enum('status', ['قيد الانتظار', 'مؤكد', 'ملغي', 'مكتمل'])->default('قيد الانتظار');
            $table->foreignId('users_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('clients_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
