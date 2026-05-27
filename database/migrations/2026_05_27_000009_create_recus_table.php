<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('paiement_id')->constrained('paiements')->onDelete('restrict');
            $table->string('numero')->unique();
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->index(['agency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recus');
    }
};
