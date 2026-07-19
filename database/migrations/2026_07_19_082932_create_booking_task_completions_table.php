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
        Schema::create('booking_task_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained('package_tasks')->cascadeOnDelete();
            $table->string('proof_path')->nullable(); // photo path for photo tasks
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['booking_id', 'task_id']); // one completion per task per booking
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_task_completions');
    }
};
