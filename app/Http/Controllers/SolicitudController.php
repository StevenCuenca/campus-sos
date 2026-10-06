<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SolicitudController extends Controller
{
    // READ - Mostrar las solicitudes del usuario autenticado
    public function index(): Response
    {
        $solicitudes = Solicitud::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($solicitud) {
                return [
                    'id' => $solicitud->id,
                    'titulo' => $solicitud->titulo,
                    'descripcion' => $solicitud->descripcion,
                    'categoria' => $solicitud->categoria,
                    'ubicacion' => $solicitud->ubicacion,
                    'urgencia' => $solicitud->urgencia,

                    'inicia_en' => $solicitud->inicia_en?->toISOString(),
                    'expira_en' => $solicitud->expira_en?->toISOString(),

                    // El sistema calcula el estado automáticamente
                    'estado_temporal' => $solicitud->estadoTemporal(),
                ];
            });

        return Inertia::render('Solicitudes/Index', [
            'solicitudes' => $solicitudes,
        ]);
    }

    // Mostrar formulario para crear una solicitud
    public function create(): Response
    {
        return Inertia::render('Solicitudes/Create');
    }

    // CREATE - Guardar una nueva solicitud
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'categoria' => ['required', 'string', 'max:100'],
            'ubicacion' => ['required', 'string', 'max:255'],
            'urgencia' => ['required', 'in:baja,media,alta'],

            'inicia_en' => ['required', 'date'],
            'expira_en' => ['required', 'date', 'after:inicia_en'],
        ]);

        $request->user()->solicitudes()->create([
            ...$datos,
            'estado' => 'activa',
        ]);

        return redirect()
            ->route('solicitudes.index')
            ->with('success', 'Solicitud creada correctamente.');
    }

    // Mostrar formulario para editar
    public function edit(Solicitud $solicitud): Response
    {
        // Solo el propietario puede editar su solicitud
        abort_unless(
            $solicitud->user_id === auth()->id(),
            403
        );

        // Una solicitud expirada no puede editarse
        abort_if(
            $solicitud->estadoTemporal() === 'expirada',
            403,
            'Esta solicitud ya expiró y no puede editarse.'
        );

        return Inertia::render('Solicitudes/Edit', [
            'solicitud' => $solicitud,
        ]);
    }

    // UPDATE - Actualizar una solicitud
    public function update(
        Request $request,
        Solicitud $solicitud
    ): RedirectResponse {

        // Solo el propietario puede modificarla
        abort_unless(
            $solicitud->user_id === auth()->id(),
            403
        );

        // Una solicitud expirada no puede modificarse
        abort_if(
            $solicitud->estadoTemporal() === 'expirada',
            403,
            'Esta solicitud ya expiró y no puede modificarse.'
        );

        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'categoria' => ['required', 'string', 'max:100'],
            'ubicacion' => ['required', 'string', 'max:255'],
            'urgencia' => ['required', 'in:baja,media,alta'],

            'inicia_en' => ['required', 'date'],
            'expira_en' => ['required', 'date', 'after:inicia_en'],
        ]);

        $solicitud->update($datos);

        return redirect()
            ->route('solicitudes.index')
            ->with('success', 'Solicitud actualizada correctamente.');
    }

    // DELETE - Eliminar una solicitud
    public function destroy(Solicitud $solicitud): RedirectResponse
    {
        // Solo el propietario puede eliminarla
        abort_unless(
            $solicitud->user_id === auth()->id(),
            403
        );

        $solicitud->delete();

        return redirect()
            ->route('solicitudes.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }
}
