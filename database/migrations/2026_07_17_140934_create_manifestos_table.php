<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manifestos', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('container_id')->unique();

            $table->string('destination');

            $table->decimal('original_weight', 10, 2);

            $table->string('original_unit', 2);

            $table->decimal('weight_kg', 10, 2);

            $table->boolean('hazmat')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manifestos');
    }
};
