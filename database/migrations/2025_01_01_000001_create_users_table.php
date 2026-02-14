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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->enum('plan_tier', ['free', 'pro', 'unlimited', 'company'])->default('free');
            $table->string('preferred_domain', 100)->default('nestqr');
            $table->string('custom_logo_path', 255)->nullable();
            $table->string('custom_brand_color', 7)->nullable();
            $table->enum('theme_preference', ['light', 'dark'])->default('light');
            $table->boolean('is_admin')->default(false);
            $table->unsignedBigInteger('company_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
