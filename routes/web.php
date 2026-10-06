<?php

use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::inertia('/', 'Welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
|
| Para acceder al Dashboard o al CRUD de solicitudes,
| el usuario debe haber iniciado sesión.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard CampusSOS
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        // Obtener todas las solicitudes del usuario autenticado
        $solicitudes = auth()->user()
            ->solicitudes()
            ->latest()
            ->get();

        // Contar solicitudes activas
        $activas = $solicitudes
            ->filter(fn ($solicitud) => $solicitud->estadoTemporal() === 'activa'
            )
            ->count();

        // Contar solicitudes programadas
        $programadas = $solicitudes
            ->filter(fn ($solicitud) => $solicitud->estadoTemporal() === 'programada'
            )
            ->count();

        // Contar solicitudes expiradas
        $expiradas = $solicitudes
            ->filter(fn ($solicitud) => $solicitud->estadoTemporal() === 'expirada'
            )
            ->count();

        // Enviar información al Dashboard.vue
        return Inertia::render('Dashboard', [

            /*
            |--------------------------------------------------------------
            | Estadísticas
            |--------------------------------------------------------------
            */

            'estadisticas' => [
                'total' => $solicitudes->count(),
                'activas' => $activas,
                'programadas' => $programadas,
                'expiradas' => $expiradas,
            ],

            /*
            |--------------------------------------------------------------
            | Últimos 3 SOS publicados
            |--------------------------------------------------------------
            */

            'recientes' => $solicitudes
                ->take(3)
                ->map(fn ($solicitud) => [

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

    Route::resource(
        'solicitudes',
        SolicitudController::class
    )
        ->except(['show'])
        ->parameters([
            'solicitudes' => 'solicitud',
        ]);

});

require __DIR__.'/settings.php';
