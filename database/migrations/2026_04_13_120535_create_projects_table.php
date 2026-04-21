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
        Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('teach_stack')->nullable();
    $table->string('image')->nullable();
    $table->string('project_link')->nullable();
    $table->string('github_link')->nullable();
    $table->unsignedBigInteger('category_id')->nullable();
    $table->enum('status', ['live', 'dev', 'archived'])->default('dev');
    $table->integer('views')->default(0);
    $table->timestamps();

    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
    
};
