<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tarea_etiqueta', function (Blueprint $table) {
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->onDelete('cascade');
            $table->primary(['tarea_id', 'etiqueta_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tarea_etiqueta');
    }
};