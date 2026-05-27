<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->foreignId('proprietaire_id')->constrained('proprietaires')->onDelete('restrict');
            $table->string('description');
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();
            $table->enum('type', ['appartement', 'chambre', 'studio', 'villa', 'bureau', 'magasin', 'autre'])->default('appartement');
            $table->integer('nombre_unites')->default(1);
            $table->timestamps();

            $table->index(['agency_id', 'zone_id']);
            $table->index('proprietaire_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biens');
    }
};
