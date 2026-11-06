
<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Facultad {
    id: number;
    nombre: string;
}

interface Estudiante {
    id: number;
    name: string;
    email: string;
    facultad_id: number | null;
    facultad: Facultad | null;
}

const props = defineProps<{
    estudiantes: Estudiante[];
    facultades: Facultad[];
}>();

// Selección independiente para cada estudiante
const selecciones = reactive<Record<number, string>>({});

for (const estudiante of props.estudiantes) {
    selecciones[estudiante.id] =
        estudiante.facultad_id?.toString() ?? '';
}

const guardando = ref<number | null>(null);
const errores = reactive<Record<number, string>>({});
const mensaje = ref('');

function guardar(estudiante: Estudiante) {
    const facultadId = selecciones[estudiante.id];

    errores[estudiante.id] = '';
    mensaje.value = '';

    if (!facultadId) {
        errores[estudiante.id] = 'Selecciona una facultad.';
        return;
    }

    guardando.value = estudiante.id;

    router.put(
        `/admin/estudiantes/${estudiante.id}`,
        {
            facultad_id: Number(facultadId),
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                mensaje.value =
                    `Facultad de ${estudiante.name} actualizada correctamente.`;
            },
            onError: (respuesta) => {
                errores[estudiante.id] =
                    respuesta.facultad_id ??
                    'No se pudo guardar la facultad.';
            },
            onFinish: () => {
                guardando.value = null;
            },
        }
    );
}
</script>

<template>
    <AppLayout>
        <Head title="Administrar estudiantes" />

        <main class="min-h-screen bg-[#F8F5F3] p-6 md:p-8">
            <div class="mx-auto max-w-6xl">

                <div class="mb-8">
                    <p
                        class="mb-2 text-xs font-bold uppercase tracking-widest text-[#7A1631]"
                    >
                        CampusSOS / Administración
                    </p>

                    <h1 class="text-3xl font-bold text-[#30171E]">
                        Gestión de estudiantes
                    </h1>

                    <p class="mt-2 text-[#6F6266]">
                        Asigna a cada estudiante una facultad para
                        organizar la participación en el Core de Colaboración.
                    </p>
                </div>

                <div
                    v-if="mensaje"
                    role="status"
                    class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800"
                >
                    {{ mensaje }}
                </div>

                <div
                    v-if="facultades.length === 0"
                    class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900"
                >
                    Primero debes registrar al menos una facultad
                    en el módulo de Facultades.
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-[#E9DEE1] bg-white shadow-sm"
                >
                    <div class="border-b border-[#EEE5E7] px-6 py-5">
                        <h2 class="text-lg font-bold text-[#30171E]">
                            Estudiantes registrados
                        </h2>

                        <p class="mt-1 text-sm text-[#786C70]">
                            Total: {{ estudiantes.length }}
                        </p>
                    </div>

                    <div
                        v-if="estudiantes.length === 0"
                        class="px-6 py-16 text-center"
                    >
                        <p class="text-lg font-semibold text-[#30171E]">
                            Todavía no hay estudiantes registrados.
                        </p>

                        <p class="mt-2 text-sm text-[#786C70]">
                            Los usuarios que se registren con rol
                            estudiante aparecerán aquí.
                        </p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full min-w-[800px] text-left">
                            <thead class="bg-[#F9F4F5]">
                                <tr class="text-sm text-[#6F6266]">
                                    <th class="px-6 py-4 font-semibold">
                                        Estudiante
                                    </th>
                                    <th class="px-6 py-4 font-semibold">
                                        Facultad actual
                                    </th>
                                    <th class="px-6 py-4 font-semibold">
                                        Asignar facultad
                                    </th>
                                    <th class="px-6 py-4 text-right font-semibold">
                                        Acción
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="estudiante in estudiantes"
                                    :key="estudiante.id"
                                    class="border-t border-[#F0E8EA]"
                                >
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-[#30171E]">
                                            {{ estudiante.name }}
                                        </p>
                                        <p class="mt-1 text-sm text-[#786C70]">
                                            {{ estudiante.email }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-[#574A4E]">
                                        {{
                                            estudiante.facultad?.nombre ??
                                            'Sin asignar'
                                        }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <select
                                            v-model="selecciones[estudiante.id]"
                                            :aria-label="`Facultad de ${estudiante.name}`"
                                            class="w-full min-w-[220px] rounded-xl border border-[#D9CDD0] bg-white px-3 py-3 text-[#30171E] outline-none focus:border-[#7A1631]"
                                        >
                                            <option value="">
                                                Selecciona una facultad
                                            </option>

                                            <option
                                                v-for="facultad in facultades"
                                                :key="facultad.id"
                                                :value="String(facultad.id)"
                                            >
                                                {{ facultad.nombre }}
                                            </option>
                                        </select>

                                        <p
                                            v-if="errores[estudiante.id]"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ errores[estudiante.id] }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <button
                                            type="button"
                                            :disabled="
                                                guardando !== null ||
                                                facultades.length === 0
                                            "
                                            class="rounded-xl bg-[#7A1631] px-4 py-3 font-semibold text-white transition hover:bg-[#5D1025] disabled:cursor-not-allowed disabled:opacity-60"
                                            @click="guardar(estudiante)"
                                        >
                                            {{
                                                guardando === estudiante.id
                                                    ? 'Guardando...'
                                                    : 'Guardar'
                                            }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </AppLayout>
</template>
