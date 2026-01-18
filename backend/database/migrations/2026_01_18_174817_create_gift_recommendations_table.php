<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gift_recommendations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('person_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('occasion');
            $table->integer('budget_min')->nullable();
            $table->integer('budget_max')->nullable();

            $table->text('ai_profile_summary');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_recommendations');
    }
};