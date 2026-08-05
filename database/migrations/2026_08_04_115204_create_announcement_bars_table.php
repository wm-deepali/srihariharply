<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_bars', function (Blueprint $table) {
            $table->id();

            $table->string('message')->nullable();

            $table->string('link_text')->nullable();
            $table->string('link_url')->nullable();

            $table->string('bg_color', 7)->default('#1F5552'); // dark green, same as hero banner
            $table->string('text_color', 7)->default('#FFFFFF');

            $table->boolean('is_active')->default(1);
            $table->boolean('is_dismissible')->default(1); // customer can close it

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_bars');
    }
};