<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {        
        Schema::table('congregacoes', function (Blueprint $table) {            
            $table->unsignedBigInteger('dirigente_membro_id')->nullable()->after('cep');
            $table->foreign('dirigente_membro_id')->references('id')->on('membros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('congregacoes', function (Blueprint $table) {
            $table->dropForeign(['dirigente_membro_id']);
            $table->dropColumn('dirigente_membro_id');
        });
    }
};
