<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('informes_marketing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->string('titulo', 100);
            $table->text('descripcion')->nullable();
            $table->text('url_archivo');
            $table->enum('tipo', ['campaña', 'calendario', 'otro'])->default('campaña');
            $table->boolean('visible_para_cliente')->default(true);
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_subida')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('informes_marketing');
    }
};