<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluaciones_desempeno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajador_id')->constrained('trabajadores')->onDelete('cascade');
            $table->integer('mes');
            $table->integer('anio');
            $table->integer('total_tareas')->default(0);
            $table->integer('tareas_a_tiempo')->default(0);
            $table->integer('tareas_retrasadas')->default(0);
            $table->decimal('porcentaje_cumplimiento', 5, 2)->nullable();
            $table->enum('calificacion', ['excelente', 'bueno', 'regular', 'malo'])->default('bueno');
            $table->text('observaciones')->nullable();
            $table->timestamp('generado_el')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluaciones_desempeno');
    }
};