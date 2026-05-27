<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->string('motif');
            $table->text('description')->nullable();
            $table->enum('statut', ['ouverte', 'en_cours', 'resolue'])->default('ouverte');
            $table->foreignId('traite_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['agency_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
