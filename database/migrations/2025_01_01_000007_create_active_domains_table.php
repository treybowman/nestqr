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
        Schema::create('active_domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain', 100)->unique();
            $table->string('market_name', 100);
            $table->boolean('is_active')->default(true);
            $table->date('launched_at')->nullable();
            $table->timestamps();

            $table->index(['domain', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_domains');
    }
};
