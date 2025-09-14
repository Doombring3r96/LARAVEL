<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('piezas_graficas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->enum('tipo', ['arte publicitario', 'logotipo', 'papelería', 'otro']);
            $table->string('titulo', 100)->nullable();
            $table->text('copy')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['en diseño', 'en revisión', 'aprobado', 'rechazado'])->default('en diseño');
            $table->text('url_archivo')->nullable();
            $table->date('fecha_entrega_estimada')->nullable();
            $table->date('fecha_entrega_real')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('piezas_graficas');
    }
};