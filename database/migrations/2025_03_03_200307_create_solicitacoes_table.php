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
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('muda_id')->constrained()->onDelete('cascade');
            $table->foreignId('solicitacao_tipos_id')->constrained('solicitacao_tipos')->onDelete('cascade');
            $table->foreignId('solicitacao_status_id')->constrained('solicitacao_status')->onDelete('cascade');
            $table->bigInteger('Rejected_by')->nullable();


            $table->timestamps();
            $table->timestamp(
                'rejected_at'
            )->nullable();
            $table->timestamp(
                'finished_at'
            )->nullable();
            $table->timestamp(
                'canceled_at'
            )->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
    }
};
