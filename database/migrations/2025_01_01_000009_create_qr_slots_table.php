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
        Schema::create('qr_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('icon_id')->constrained('icons');
            $table->string('short_code', 10)->unique();
            $table->string('qr_image_path', 255)->nullable();
            $table->unsignedBigInteger('current_listing_id')->nullable();
            $table->unsignedInteger('total_scans')->default(0);
            $table->timestamp('icon_locked_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'short_code', 'current_listing_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_slots');
    }
};
