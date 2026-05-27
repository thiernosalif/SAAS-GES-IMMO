<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comptabilites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->enum('type', ['entree', 'sortie']);
            $table->decimal('montant', 12, 2);
            $table->text('motif');
            $table->string('categorie')->nullable();
            $table->foreignId('reference_paiement_id')->nullable()->constrained('paiements')->nullOnDelete();
            $table->foreignId('saisi_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['agency_id']);
            $table->index(['agency_id', 'type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comptabilites');
    }
};
