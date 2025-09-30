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
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id'); // Foreign key to people.id (bigint)
            $table->string('url');
            $table->integer('order');
            $table->timestamps();
            
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->index(['person_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pictures');
    }
};