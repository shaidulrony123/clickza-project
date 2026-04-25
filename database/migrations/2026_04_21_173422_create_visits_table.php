<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('country_name')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('path')->nullable();
            $table->boolean('is_unique')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
