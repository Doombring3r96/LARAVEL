<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('trabajador_id')->nullable()->constrained('trabajadores')->onDelete('set null');
            $table->string('titulo', 100);
            $table->text('descripcion')->nullable();
            $table->enum('prioridad', ['alta', 'media', 'baja'])->default('media');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->date('fecha_fin_real')->nullable();
            $table->enum('estado', ['pendiente', 'en curso', 'en revisión', 'completada', 'retrasada'])->default('pendiente');
            $table->timestamps();
            
            $table->index('fecha_fin_estimada');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tareas');
    }
};