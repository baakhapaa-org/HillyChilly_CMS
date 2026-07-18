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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_intent_id')->nullable()->after('payment_method');
            $table->string('payment_status')->default('unpaid')->after('payment_intent_id');
            // payment_status: unpaid | pending | paid | failed
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_intent_id', 'payment_status']);
        });
    }
};
