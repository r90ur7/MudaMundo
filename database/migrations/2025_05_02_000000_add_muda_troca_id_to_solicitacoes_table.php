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
        // Adiciona apenas se não existir
        if (!Schema::hasColumn('solicitacoes', 'muda_troca_id')) {
            Schema::table('solicitacoes', function (Blueprint $table) {
                $table->foreignId('muda_troca_id')
                      ->nullable()
                      ->after('muda_id')
                      ->constrained('mudas')
                      ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes', function (Blueprint $table) {
            if (Schema::hasColumn('solicitacoes', 'muda_troca_id')) {
                $table->dropForeign(['muda_troca_id']);
                $table->dropColumn('muda_troca_id');
            }
        });
    }
};
