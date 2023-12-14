<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consagracoes', function (Blueprint $table) {
            $table->id();
            $table->date('data_consagracao');
            $table->string('ministerio', 75)->nullable();

            $table->unsignedBigInteger('membro_id');
            $table->foreign('membro_id')->references('id')->on('membros');

            $table->unsignedBigInteger('cargo_id');
            $table->foreign('cargo_id')->references('id')->on('cargos');            

            $table->unsignedBigInteger('file_cert_consagracao')->nullable();
            $table->foreign('file_cert_consagracao')->references('id')->on('files');

            $table->enum('situacao', ['ATIVO', 'INATIVO']);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consagracao');
    }
};
