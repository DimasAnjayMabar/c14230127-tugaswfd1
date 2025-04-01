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
        Schema::create('detailed_build', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pc_builds_id')->constrained('pc_builds')->onDelete('cascade');
            $table->foreignId('pc_parts_id')->constrained('pc_parts')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailed_build');
    }
};
