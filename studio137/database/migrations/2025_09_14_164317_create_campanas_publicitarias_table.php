<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campanas_publicitarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('responsable_marketing_id')->nullable()->constrained('trabajadores')->onDelete('set null');
            $table->string('nombre_campana', 100);
            $table->text('objetivo')->nullable();
            $table->enum('plataforma', ['Facebook', 'Instagram', 'Google', 'TikTok', 'Otro'])->default('Otro');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->decimal('presupuesto', 10, 2)->nullable();
            $table->enum('estado', ['planificada', 'en curso', 'finalizada', 'cancelada'])->default('planificada');
            $table->text('url_archivo')->nullable();
            $table->timestamps();
            
            $table->index('fecha_inicio');
            $table->index('fecha_fin');
        });
    }

    public function down()
    {
        Schema::dropIfExists('campanas_publicitarias');
    }
};