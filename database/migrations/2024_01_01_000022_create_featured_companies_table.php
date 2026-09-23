<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 45);
            $table->string('logo_url', 255)->nullable();
            $table->date('contract_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_companies');
    }
};
