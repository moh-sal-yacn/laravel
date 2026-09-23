<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users_has_roles', function (Blueprint $table) {
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('roles_id')->constrained('roles')->cascadeOnDelete();
            $table->primary(['users_id', 'roles_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_has_roles');
    }
};
