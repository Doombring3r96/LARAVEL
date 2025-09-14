<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\CalendarioPublicacionController;
use App\Http\Controllers\ArteCalendarioController;
use App\Http\Controllers\CampanaPublicitariaController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\EvaluacionDesempenoController;
use App\Http\Controllers\InformeMarketingController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\PiezaGraficaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('clientes', ClienteController::class);
Route::resource('servicios', ServicioController::class);
Route::resource('actividades', ActividadController::class);
Route::resource('trabajadores', TrabajadorController::class);
Route::resource('calendarios-publicacion', CalendarioPublicacionController::class);
Route::resource('artes-calendario', ArteCalendarioController::class);
Route::resource('campanas-publicitarias', CampanaPublicitariaController::class);
Route::resource('etiquetas', EtiquetaController::class);
Route::resource('evaluaciones-desempeno', EvaluacionDesempenoController::class);
Route::resource('informes-marketing', InformeMarketingController::class);
Route::resource('notificaciones', NotificacionController::class);
Route::resource('pagos', PagoController::class);
Route::resource('tareas', TareaController::class);
Route::resource('piezas-graficas', PiezaGraficaController::class);
require __DIR__.'/auth.php';
