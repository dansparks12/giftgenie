<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('person_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('person_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type');
            $table->text('value');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('person_attributes');
    }
};