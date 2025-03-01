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
        Schema::create('mudas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->bigInteger('Donated_to')->nullable();
            $table->foreignId('tipos_id')->constrained()->onDelete('cascade');
            $table->foreignId('muda_status_id')->constrained()->onDelete('cascade');
            $table->foreignId('especie_id')->constrained()->onDelete('cascade');

            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('foto_url')->nullable();
            $table->integer('quantidade')->default(1);
            $table->string('cep', 8);
            $table->string('logradouro', 150);
            $table->string('numero', 150);
            $table->string('complemento', 150)->nullable();
            $table->string('bairro', 150);
            $table->string('cidade', 150);
            $table->string('uf', 2);
            $table->timestamps();
            $table->timestamps(
                'donated_at'
            );
            $table->timestamps(
                'disabled_at'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mudas');
    }
};