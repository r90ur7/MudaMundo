<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mudas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('donated_to');
            $table->unsignedBigInteger('tipo_id');
            $table->unsignedBigInteger('especie_id');
            $table->unsignedBigInteger('status_id');
            $table->string('foto_url', 255)->nullable();
            $table->text('descricao')->nullable();

            $table->timestamp('disabled_at')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('id_users')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('donated_to')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('tipo_id')
                ->references('id')->on('tipos')
                ->onDelete('cascade');

            $table->foreign('especie_id')
                ->references('id')->on('especies')
                ->onDelete('cascade');

            $table->foreign('status_id')
                ->references('id')->on('muda_status')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mudas');
    }
};