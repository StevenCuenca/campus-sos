<?php

namespace App\Http\Controllers;

use App\Models\Facultad;
use Inertia\Inertia;
use Inertia\Response;

class RankingController extends Controller
{
    public function index(): Response
    {
        $facultades = Facultad::query()
            ->withCount([
                'users as participantes' => function ($query) {
                    $query->where('role', 'estudiante');
                },
            ])
            ->get()
            ->map(function ($facultad) {
                $participacion = $facultad->total_estudiantes > 0
                    ? ($facultad->participantes / $facultad->total_estudiantes) * 100
                    : 0;

                return [
                    'id' => $facultad->id,
                    'nombre' => $facultad->nombre,
                    'participantes' => $facultad->participantes,
                    'total_estudiantes' => $facultad->total_estudiantes,
                    'participacion' => round($participacion, 2),
                ];
            })
            ->sortByDesc('participacion')
            ->values()
            ->map(function ($facultad, $indice) {
                return [
                    ...$facultad,
                    'posicion' => $indice + 1,
                ];
            });

        return Inertia::render('Ranking/Index', [
            'facultades' => $facultades,
            'totalParticipantes' => $facultades->sum('participantes'),
            'participacionPromedio' => round(
                $facultades->avg('participacion') ?? 0,
                2
            ),
        ]);
    }
}
