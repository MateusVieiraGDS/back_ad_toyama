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
        Schema::create('batismos', function (Blueprint $table) {
            $table->id();

            $table->date('data_batismo');
            $table->string('ministerio', '75')->nullable();

            $table->unsignedBigInteger('file_cert_batismo')->nullable();
            $table->foreign('file_cert_batismo')->references('id')->on('files');

            $table->unsignedBigInteger('membro_id')->unique();
            $table->foreign('membro_id')->references('id')->on('membros');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batismos');
    }
};
