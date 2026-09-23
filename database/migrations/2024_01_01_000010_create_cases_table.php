<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number', 45)->unique();
            $table->string('case_title', 255);
            $table->enum('case_status', ['قيد النظر', 'مؤجلة', 'منتهية', 'مؤرشفة'])->default('قيد النظر');
            $table->timestamp('archived_at')->nullable();
            $table->date('opened_at');
            $table->text('description')->nullable();
            $table->foreignId('clients_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('categories_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('courts_id')->constrained('courts')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
