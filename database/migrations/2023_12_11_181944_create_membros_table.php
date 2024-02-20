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
        Schema::create('membros', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();


            $table->char('rg', 10)->unique();
            $table->char('cpf', 11)->unique();
            $table->date('nasc');
            $table->enum('sexo', ['MASCULINO', 'FEMININO']);
            $table->enum('estado_civil', ['SOLTEIRO', 'CASADO', 'SEPARADO', 'DIVORCIADO', 'VIUVO']);
            $table->string('naturalidade', 35);
            $table->char('uf', 2);
            $table->enum('situacao', ['ATIVO', 'INATIVO']);
            $table->enum('pendencia', ['SIM', 'NAO']);

            $table->unsignedBigInteger('file_cert_nascimento');
            $table->foreign('file_cert_nascimento')->references('id')->on('files');

            $table->unsignedBigInteger('file_doc_image');       
            $table->foreign('file_doc_image')->references('id')->on('files');

            $table->unsignedBigInteger('file_foto');
            $table->foreign('file_foto')->references('id')->on('files');

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
