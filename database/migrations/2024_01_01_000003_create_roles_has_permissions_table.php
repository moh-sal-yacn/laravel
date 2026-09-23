<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles_has_permissions', function (Blueprint $table) {
            $table->foreignId('roles_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permissions_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['roles_id', 'permissions_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles_has_permissions');
    }
};
