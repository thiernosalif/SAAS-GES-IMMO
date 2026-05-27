<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locataires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->string('cin')->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('coordonne_pro')->nullable();
            $table->string('mobileref')->nullable();
            $table->timestamps();

            $table->index(['agency_id']);
            $table->index(['nom', 'prenom']);
            $table->index('telephone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locataires');
    }
};
