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
        Schema::create('category_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->cascadeOnDelete()->constrained('tasks');
            $table->unique(['task_id','category_id']);
            $table->foreignId('category_id')->cascadeOnDelete()->constrained('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category__tasks');
    }
};
