<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('galleries')->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->string('status')->default('active'); // active | block
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_details');
    }
};