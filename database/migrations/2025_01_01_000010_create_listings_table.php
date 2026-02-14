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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('qr_slot_id')->nullable()->constrained('qr_slots')->nullOnDelete();
            $table->string('address', 255);
            $table->string('city', 100)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 20)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedTinyInteger('beds')->nullable();
            $table->decimal('baths', 3, 1)->nullable();
            $table->unsignedInteger('sqft')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'pending', 'sold', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status', 'qr_slot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
