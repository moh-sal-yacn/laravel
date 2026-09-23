<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('clients_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('categories_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('cases_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('bookings_id')->nullable()->constrained('bookings')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_ratings');
    }
};
