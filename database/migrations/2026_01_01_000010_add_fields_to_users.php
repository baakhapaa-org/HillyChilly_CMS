<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->nullable()->after('email');
            $table->unsignedBigInteger('points_balance')->default(0)->after('avatar_url');
            $table->boolean('is_admin')->default(false)->after('points_balance');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_url', 'points_balance', 'is_admin']);
        });
    }
};
