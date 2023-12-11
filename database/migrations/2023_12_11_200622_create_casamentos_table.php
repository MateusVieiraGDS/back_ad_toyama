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
        Schema::create('casamentos', function (Blueprint $table) {
            $table->id();

            $table->date('data_casamento');
            $table->string('cert_casamento_url', 125)->nullable();

            $table->unsignedBigInteger('user_conjuge_1');
            $table->foreign('user_conjuge_1')->references('id')->on('users');

            $table->unsignedBigInteger('user_conjuge_2');
            $table->foreign('user_conjuge_2')->references('id')->on('users');

            $table->unique(['user_conjuge_1', 'user_conjuge_2']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casamentos');
    }
};
