<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases_has_users', function (Blueprint $table) {
            $table->foreignId('cases_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role_in_case', ['محامي أساسي', 'محامي مساعد', 'موكل', 'شاهد'])->default('محامي أساسي');
            $table->timestamp('assigned_at')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->primary(['cases_id', 'users_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases_has_users');
    }
};
