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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50); 
            $table->string('salary', 20);
            $table->string('location'); 
            $table->text('requirements'); 
            $table->string('description', 300);
            $table->enum('type', ['full time', 'part time', 'freelance']);
            $table->integer('applicants_number');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('employer_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
