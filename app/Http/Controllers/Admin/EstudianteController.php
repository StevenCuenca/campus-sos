<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facultad;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class EstudianteController extends Controller
{
    // Listar estudiantes y facultades disponibles
    public function index(): Response
    {
        $estudiantes = User::query()
            ->where('role', 'estudiante')
            ->with('facultad:id,nombre')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'facultad_id',
            ]);

        $facultades = Facultad::query()
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
            ]);

        return Inertia::render('Admin/Estudiantes/Index', [
            'estudiantes' => $estudiantes,
            'facultades' => $facultades,
        ]);
    }

    // Asignar o cambiar la facultad de un estudiante
    public function update(
        Request $request,
        User $estudiante
    ): RedirectResponse {
        // Impedir modificar administradores desde este módulo
        abort_unless($estudiante->role === 'estudiante', 403);

        // Validación obligatoria en el backend
        $datos = $request->validate([
            'facultad_id' => [
                'required',
                'integer',
                Rule::exists('facultades', 'id'),
            ],
        ]);

        $estudiante->facultad_id = $datos['facultad_id'];
        $estudiante->save();

        return redirect()
            ->route('admin.estudiantes.index')
            ->with(
                'success',
                'Facultad del estudiante actualizada correctamente.'
            );
    }
}
