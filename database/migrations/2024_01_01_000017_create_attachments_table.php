<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('related_id');
            $table->enum('related_type', ['case', 'contract', 'user'])->default('case');
            $table->string('file_url', 255);
            $table->string('file_type', 45)->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();

            $table->index(['related_id', 'related_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
