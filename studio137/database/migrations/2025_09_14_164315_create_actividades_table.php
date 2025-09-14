<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->smallInteger('orden')->nullable();
            $table->date('fecha_inicio_estimada')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->date('fecha_inicio_real')->nullable();
            $table->date('fecha_fin_real')->nullable();
            $table->enum('estado', ['pendiente', 'en progreso', 'en revisión', 'finalizada', 'cancelada'])->default('pendiente');
            $table->timestamps();
            
            $table->index('fecha_fin_estimada');
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividades');
    }
};