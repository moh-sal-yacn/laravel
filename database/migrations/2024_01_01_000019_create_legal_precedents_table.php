<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_precedents', function (Blueprint $table) {
            $table->id();
            $table->enum('source', ['محكمة النقض', 'محكمة الاستئناف', 'محكمة عليا', 'أخرى']);
            $table->string('title', 255);
            $table->text('summary');
            $table->string('external_link', 255)->nullable();
            $table->date('ruling_date');
            $table->foreignId('cases_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_precedents');
    }
};
