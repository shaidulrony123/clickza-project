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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('badge')->nullable();
            $table->string('name');
            $table->text('description');
            // summernote
            $table->text('long_description')->nullable();
            $table->string('price');
            $table->string('discount')->nullable();
            $table->string('image');
            $table->string('tag')->nullable();
            $table->string('icon')->nullable();
            $table->string('live_link');
            $table->string('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
