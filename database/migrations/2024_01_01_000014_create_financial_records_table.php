<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['دفعة عقد', 'رسوم قضية', 'مصروف', 'أخرى']);
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->foreignId('cases_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('clients_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
