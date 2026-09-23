<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_channels', function (Blueprint $table) {
            $table->id();
            $table->enum('channel_type', ['بريد إلكتروني', 'هاتف', 'واتساب', 'نموذج الموقع']);
            $table->text('message');
            $table->enum('status', ['جديد', 'قيد المعالجة', 'مغلق'])->default('جديد');
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_channels');
    }
};
