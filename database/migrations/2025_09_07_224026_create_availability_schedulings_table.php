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
        Schema::create('availability_schedulings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('availability_id')->constrained();
            $table->foreignId('scheduling_id')->constrained();
            $table->unique(['availability_id', 'scheduling_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availability_schedulings');
    }
};
