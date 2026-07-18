<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cms_blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image_url')->nullable();
            $table->longText('content');
            $table->string('excerpt')->nullable();
            $table->string('category')->default('adventure');
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cms_blogs'); }
};
