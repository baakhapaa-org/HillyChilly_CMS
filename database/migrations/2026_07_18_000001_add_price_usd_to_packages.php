<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('packages', function (Blueprint $table) {
            // USD price in dollars (e.g. 29.99) — multiply by 100 for Stripe cents
            $table->decimal('price_usd', 8, 2)->default(0)->after('price_npr');
        });
    }
    public function down(): void {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('price_usd');
        });
    }
};
