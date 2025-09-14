<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo_pago', ['cliente', 'trabajador']);
            $table->foreignId('servicio_id')->nullable()->constrained('servicios')->onDelete('set null');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->text('url_comprobante')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            $table->index('fecha_pago');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
};