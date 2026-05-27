<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('situations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('proprietaire_id')->constrained('proprietaires')->onDelete('restrict');
            $table->date('periode_debut');
            $table->date('periode_fin');
            $table->enum('type', ['mensuelle', 'annuelle'])->default('mensuelle');
            $table->decimal('total_loyers_percus', 12, 2)->default(0);
            $table->decimal('total_charges', 12, 2)->default(0);
            $table->decimal('commission_agence', 12, 2)->default(0);
            $table->decimal('net_proprietaire', 12, 2)->default(0);
            $table->enum('statut', ['brouillon', 'validee', 'envoyee'])->default('brouillon');
            $table->foreignId('genere_par')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->index(['agency_id', 'proprietaire_id']);
        });

        Schema::create('situation_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('situation_id')->constrained('situations')->onDelete('cascade');
            $table->foreignId('contrat_id')->nullable()->constrained('contrats')->nullOnDelete();
            $table->string('locataire_nom');
            $table->string('bien_description');
            $table->date('periode');
            $table->decimal('loyer_du', 12, 2)->default(0);
            $table->decimal('montant_percu', 12, 2)->default(0);
            $table->decimal('ecart', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('situation_lignes');
        Schema::dropIfExists('situations');
    }
};
