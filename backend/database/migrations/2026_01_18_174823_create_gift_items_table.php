<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gift_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gift_recommendation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->integer('price_min')->nullable();
            $table->integer('price_max')->nullable();

            $table->string('source')->nullable();
            $table->text('url')->nullable();

            $table->text('ai_reason');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_items');
    }
};