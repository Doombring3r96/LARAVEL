<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calendarios_publicacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('responsable_marketing_id')->nullable()->constrained('trabajadores')->onDelete('set null');
            $table->string('mes_publicacion', 20);
            $table->year('anio_publicacion');
            $table->text('url_documento')->nullable();
            $table->enum('estado', ['borrador', 'revisión', 'aprobado', 'publicado'])->default('borrador');
            $table->date('fecha_creacion')->nullable();
            $table->date('fecha_aprobacion')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->timestamps();
            
            $table->index('fecha_publicacion');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendarios_publicacion');
    }
};