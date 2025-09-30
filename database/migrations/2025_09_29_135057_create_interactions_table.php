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
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); // X-User-Id header
            $table->unsignedBigInteger('person_id'); // Foreign key to people.id (bigint)
            $table->enum('action', ['like', 'dislike']);
            $table->timestamps();
            
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->unique(['user_id', 'person_id']); // Prevent duplicate likes/dislikes
            $table->index(['user_id', 'action']);
            $table->index(['person_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interactions');
    }
};