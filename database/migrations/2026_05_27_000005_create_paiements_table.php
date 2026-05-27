<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('restrict');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('restrict');
            $table->date('periode');
            $table->decimal('montant', 12, 2)->default(0);
            $table->decimal('montant_du', 12, 2)->default(0);
            $table->enum('mode_paiement', ['especes', 'virement', 'mobile_money', 'cheque'])->default('especes');
            $table->decimal('avance', 12, 2)->nullable();
            $table->decimal('acompte', 12, 2)->nullable();
            $table->decimal('complement', 12, 2)->nullable();
            $table->string('transaction_reference')->nullable();
            $table->enum('statut', ['complet', 'partiel', 'avance'])->default('complet');
            $table->foreignId('encaisse_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['agency_id']);
            $table->index('periode');
            $table->index(['contrat_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
