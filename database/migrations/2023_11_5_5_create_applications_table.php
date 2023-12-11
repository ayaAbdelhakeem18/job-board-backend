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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onUpdate('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status',['pending','accepted','rejected']);
            $table->string("name");
            $table->string("email");
            $table->text("cover_letter");
            $table->string("resume");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
