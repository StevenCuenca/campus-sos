<?php

use App\Http\Controllers\SolicitudController;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\FacultadController;
use App\Http\Controllers\Admin\EstudianteController;
use App\Http\Controllers\RankingController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD PRINCIPAL
    Route::get('/dashboard', function () {
        /** @var User $user */
        $user = auth()->user();

        $solicitudes = $user
            ->solicitudes()
            ->latest()
            ->get();

        $activas = $solicitudes
            ->filter(
                fn (Solicitud $solicitud): bool => $solicitud->estadoTemporal() === 'activa'
            )
            ->count();

        $programadas = $solicitudes
            ->filter(
                fn (Solicitud $solicitud): bool => $solicitud->estadoTemporal() === 'programada'
            )
            ->count();

        $expiradas = $solicitudes
            ->filter(
                fn (Solicitud $solicitud): bool => $solicitud->estadoTemporal() === 'expirada'
            )
            ->count();

        return Inertia::render('Dashboard', [
            'estadisticas' => [
                'total' => $solicitudes->count(),
                'activas' => $activas,
                'programadas' => $programadas,
                'expiradas' => $expiradas,
            ],

            'recientes' => $solicitudes
                ->take(3)
                ->map(fn (Solicitud $solicitud): array => [
                    'id' => $solicitud->id,
                    'titulo' => $solicitud->titulo,
                    'ubicacion' => $solicitud->ubicacion,
                    'urgencia' => $solicitud->urgencia,
                    'estado_temporal' => $solicitud->estadoTemporal(),
                    'inicia_en' => $solicitud->inicia_en?->toISOString(),
                    'expira_en' => $solicitud->expira_en?->toISOString(),
                ])
                ->values(),
        ]);
    })->name('dashboard');

    // RANKING INTERFACULTADES
    Route::get('/ranking', [RankingController::class, 'index'])
    ->name('ranking');
    // CRUD DE SOLICITUDES
    Route::resource('solicitudes', SolicitudController::class)
        ->except(['show'])
        ->parameters([
            'solicitudes' => 'solicitud',
        ]);

    // RUTAS EXCLUSIVAS DEL ADMINISTRADOR
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

    // Panel principal del administrador
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    // Administración de facultades
    Route::resource('facultades', FacultadController::class)
    ->except(['show'])
    ->parameters([
        'facultades' => 'facultad',
    ]);

    // Administración de estudiantes
    Route::get(
        'estudiantes',
        [EstudianteController::class, 'index']
    )->name('estudiantes.index');

    Route::put(
        'estudiantes/{estudiante}',
        [EstudianteController::class, 'update']
    )->name('estudiantes.update');

    });
    });

require __DIR__.'/settings.php';