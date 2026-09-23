<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_type', 45);
            $table->enum('contract_status', ['نشط', 'منتهي', 'ملغي'])->default('نشط');
            $table->text('parties');
            $table->decimal('contract_value', 10, 2)->default(0);
            $table->date('signed_at');
            $table->foreignId('clients_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
