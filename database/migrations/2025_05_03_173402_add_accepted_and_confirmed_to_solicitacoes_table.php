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
            $table->timestamp('accepted_at')->nullable()->after('finished_at');
            $table->timestamp('confirmed_at')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes', function (Blueprint $table) {
            $table->dropColumn(['accepted_at', 'confirmed_at']);
        });
    }
};
