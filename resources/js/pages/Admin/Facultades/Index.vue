
<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

interface Facultad {
    id: number;
    nombre: string;
    total_estudiantes: number;
}

defineProps<{
    facultades: Facultad[];
}>();

const page = usePage();

const mensaje = computed(() => {
    const flash = page.props.flash as
        | { success?: string }
        | undefined;

    return flash?.success;
});

function eliminarFacultad(facultad: Facultad) {
    if (
        !confirm(
            `¿Estás seguro de eliminar la facultad "${facultad.nombre}"?`
        )
    ) {
        return;
    }

    router.delete(`/admin/facultades/${facultad.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Administrar facultades" />

        <div class="min-h-screen bg-[#F8F5F3] p-6 md:p-8">
            <div class="mx-auto max-w-6xl">

                <div
                    class="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-center"
                >
                    <div>
                        <p
                            class="mb-2 text-xs font-bold uppercase tracking-widest text-[#7A1631]"
                        >
                            CampusSOS / Administración
                        </p>

                        <h1
                            class="text-3xl font-bold text-[#30171E]"
                        >
                            Gestión de facultades
                        </h1>

                        <p class="mt-2 text-[#6F6266]">
                            Administra las facultades que participan
                            en el Core de Colaboración.
                        </p>
                    </div>

                    <Link
                        href="/admin/facultades/create"
                        class="rounded-xl bg-[#7A1631] px-5 py-3 text-center font-semibold text-white transition hover:bg-[#5D1025]"
                    >
                        + Nueva facultad
                    </Link>
                </div>

                <div
                    v-if="mensaje"
                    class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800"
                >
                    {{ mensaje }}
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-[#E9DEE1] bg-white shadow-sm"
                >
                    <div
                        class="border-b border-[#EEE5E7] px-6 py-5"
                    >
                        <h2
                            class="text-lg font-bold text-[#30171E]"
                        >
                            Facultades registradas
                        </h2>

                        <p class="mt-1 text-sm text-[#786C70]">
                            Total: {{ facultades.length }}
                        </p>
                    </div>

                    <div
                        v-if="facultades.length === 0"
                        class="px-6 py-16 text-center"
                    >
                        <p class="text-lg font-semibold text-[#30171E]">
                            Todavía no hay facultades registradas.
                        </p>

                        <p class="mt-2 text-sm text-[#786C70]">
                            Registra la primera facultad para comenzar.
                        </p>

                        <Link
                            href="/admin/facultades/create"
                            class="mt-5 inline-block rounded-xl bg-[#7A1631] px-5 py-3 font-semibold text-white"
                        >
                            Registrar facultad
                        </Link>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full min-w-[650px] text-left">
                            <thead class="bg-[#F9F4F5]">
                                <tr class="text-sm text-[#6F6266]">
                                    <th class="px-6 py-4 font-semibold">
                                        ID
                                    </th>
                                    <th class="px-6 py-4 font-semibold">
                                        Facultad
                                    </th>
                                    <th class="px-6 py-4 font-semibold">
                                        Estudiantes matriculados
                                    </th>
                                    <th class="px-6 py-4 text-right font-semibold">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="facultad in facultades"
                                    :key="facultad.id"
                                    class="border-t border-[#F0E8EA]"
                                >
                                    <td class="px-6 py-4 text-[#786C70]">
                                        {{ facultad.id }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-[#30171E]">
                                        {{ facultad.nombre }}
                                    </td>

                                    <td class="px-6 py-4 text-[#574A4E]">
                                        {{
                                            facultad.total_estudiantes.toLocaleString(
                                                'es-EC'
                                            )
                                        }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div
                                            class="flex justify-end gap-3"
                                        >
                                            <Link
                                                :href="`/admin/facultades/${facultad.id}/edit`"
                                                class="rounded-lg border border-[#E5CED4] px-3 py-2 text-sm font-semibold text-[#7A1631] hover:bg-[#F9F0F2]"
                                            >
                                                Editar
                                            </Link>

                                            <button
                                                type="button"
                                                class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50"
                                                @click="eliminarFacultad(facultad)"
                                            >
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
