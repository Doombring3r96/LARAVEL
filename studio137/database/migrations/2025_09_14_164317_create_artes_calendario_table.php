<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artes_calendario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendario_id')->constrained('calendarios_publicacion')->onDelete('cascade');
            $table->foreignId('disenador_id')->nullable()->constrained('trabajadores')->onDelete('set null');
            $table->string('titulo', 100)->nullable();
            $table->text('copy')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha_publicacion_programada')->nullable();
            $table->text('url_arte')->nullable();
            $table->enum('estado', ['en diseño', 'en revisión', 'aprobado', 'publicado'])->default('en diseño');
            $table->timestamps();
            
            $table->index('fecha_publicacion_programada');
        });
    }

    public function down()
    {
        Schema::dropIfExists('artes_calendario');
    }
};