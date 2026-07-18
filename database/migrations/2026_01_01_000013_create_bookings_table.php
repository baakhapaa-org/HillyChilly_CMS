<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained();
            $table->date('start_date');
            $table->unsignedTinyInteger('participants')->default(1);
            $table->unsignedInteger('total_amount_npr');
            $table->unsignedInteger('points_reward')->default(0);
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed
            $table->string('payment_method')->nullable(); // esewa, khalti, bank, cash
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bookings'); }
};
