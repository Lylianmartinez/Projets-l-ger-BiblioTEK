<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exemplaires', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('livre_id')->constrained('livres')->cascadeOnDelete();
            $table->foreignId('statut_id')->constrained('statuts');
            $table->date('mise_en_service');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exemplaires');
    }
};
