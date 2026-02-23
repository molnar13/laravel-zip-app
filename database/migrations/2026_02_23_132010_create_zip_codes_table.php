<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zip_codes', function (Blueprint $table) {
            $table->id();
            $table->string('zip_code'); // Irányítószám
            $table->string('city');     // Település
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zip_codes');
    }
};