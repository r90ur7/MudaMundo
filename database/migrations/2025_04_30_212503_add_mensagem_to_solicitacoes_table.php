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
            $table->dropColumn('mensagem');
            $table->dropColumn('muda_troca_id');
        });
    }
};
