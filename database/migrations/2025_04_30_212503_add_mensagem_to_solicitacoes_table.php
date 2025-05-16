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
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->text('mensagem')->nullable()->after('solicitacao_status_id');
            $table->foreignId('muda_troca_id')->nullable()->after('muda_id')->constrained('mudas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes', function (Blueprint $table) {
            if (Schema::hasColumn('solicitacoes', 'mensagem')) {
                $table->dropColumn('mensagem');
            }
            if (Schema::hasColumn('solicitacoes', 'muda_troca_id')) {
                // Remove a foreign key constraint se existir (ignorado em SQLite)
                try {
                    $table->dropForeign(['muda_troca_id']);
                } catch (\Throwable $e) {
                    // Ignora erro se não existir FK ou não suportado
                }
                $table->dropColumn('muda_troca_id');
            }
        });
    }
};
