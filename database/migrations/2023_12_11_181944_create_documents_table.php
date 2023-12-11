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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();


            $table->char('rg', 10)->unique();
            $table->char('cpf', 11)->unique();
            $table->date('nasc');
            $table->enum('sexo', ['MASCULINO', 'FEMININO']);
            $table->enum('estado_civil', ['SOLTEIRO', 'CASADO', 'SEPARADO', 'DIVORCIADO', 'VIUVO']);
            $table->string('naturalidade', 35);
            $table->char('uf', 2);

            $table->string('cert_nascimento_url', 125);
            $table->string('doc_image_url', 125);       
            $table->string('foto_url', 125)->nullable();

            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos');

            $table->unsignedBigInteger('congregacao_id');
            $table->foreign('congregacao_id')->references('id')->on('congregacoes');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
