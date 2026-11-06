<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facultad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class FacultadController extends Controller
{
    // Mostrar todas las facultades
    public function index(): Response
    {
        return Inertia::render('Admin/Facultades/Index', [
            'facultades' => Facultad::orderBy('nombre')->get(),
        ]);
    }

    // Mostrar formulario de creación
    public function create(): Response
    {
        return Inertia::render('Admin/Facultades/Create');
    }

    // Guardar una facultad
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:facultades,nombre'],
            'total_estudiantes' => ['required', 'integer', 'min:1'],
        ]);

        Facultad::create($datos);

        return redirect()
            ->route('admin.facultades.index')
            ->with('success', 'Facultad creada correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Facultad $facultad): Response
    {
        return Inertia::render('Admin/Facultades/Edit', [
            'facultad' => $facultad,
        ]);
    }

    // Actualizar una facultad
    public function update(Request $request, Facultad $facultad): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('facultades', 'nombre')->ignore($facultad->id),
            ],
            'total_estudiantes' => ['required', 'integer', 'min:1'],
        ]);

        $facultad->update($datos);

        return redirect()
            ->route('admin.facultades.index')
            ->with('success', 'Facultad actualizada correctamente.');
    }

    // Eliminar una facultad
    public function destroy(Facultad $facultad): RedirectResponse
    {
        $facultad->delete();

        return redirect()
            ->route('admin.facultades.index')
            ->with('success', 'Facultad eliminada correctamente.');
    }
}
