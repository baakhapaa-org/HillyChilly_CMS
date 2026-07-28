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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firebase_uid')->nullable()->unique()->after('email');
            $table->unsignedBigInteger('baakhapaa_user_id')->nullable()->after('firebase_uid');
            $table->text('baakhapaa_token')->nullable()->after('baakhapaa_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firebase_uid', 'baakhapaa_user_id', 'baakhapaa_token']);
        });
    }
};
