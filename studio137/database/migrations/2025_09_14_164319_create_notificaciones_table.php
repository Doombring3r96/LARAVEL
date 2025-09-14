<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->text('mensaje');
            $table->boolean('leido')->default(false);
            $table->enum('origen_tipo', ['actividad', 'tarea', 'calendario', 'campaña', 'pago']);
            $table->integer('origen_id');
            $table->timestamp('fecha_generada')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notificaciones');
    }
};