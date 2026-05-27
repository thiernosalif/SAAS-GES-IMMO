<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->string('pays', 2)->nullable()->after('ville');
            $table->string('locale_defaut', 5)->default('fr')->after('pays');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('pays', 2)->nullable()->after('telephone');
            $table->string('locale', 5)->default('fr')->after('pays');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pays', 'locale']);
        });
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['pays', 'locale_defaut']);
        });
    }
};
