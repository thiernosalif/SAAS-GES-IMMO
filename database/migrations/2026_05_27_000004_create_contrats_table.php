<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('bien_id')->constrained('biens')->onDelete('restrict');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('restrict');
            $table->string('type_logement')->nullable();
            $table->decimal('loyer_mensuel', 12, 2)->default(0);
            $table->decimal('charges_mensuelles', 12, 2)->default(0);
            $table->integer('avance_loyer')->default(0);
            $table->decimal('caution', 12, 2)->default(0);
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['actif', 'resilie', 'expire'])->default('actif');
            $table->boolean('disponibilite')->default(true);
            $table->timestamps();

            $table->index(['agency_id', 'statut']);
            $table->index('locataire_id');
            $table->index('bien_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
