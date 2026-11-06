<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import {
    CalendarDays,
    Clock,
    MapPin,
    Pencil,
    Plus,
    Trash2,
} from '@lucide/vue';

interface Solicitud {
    id: number;
    titulo: string;
    descripcion: string;
    categoria: string;
    ubicacion: string;
    urgencia: 'baja' | 'media' | 'alta';
    inicia_en: string | null;
    expira_en: string | null;
    estado_temporal: 'programada' | 'activa' | 'expirada';
}

defineProps<{
    solicitudes: Solicitud[];
}>();

const formatearFecha = (fecha: string | null) => {
    if (!fecha) {
        return 'Sin fecha';
    }

    return new Intl.DateTimeFormat('es-EC', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(fecha));
};

const eliminarSolicitud = (id: number) => {
    const confirmar = window.confirm(
        '¿Estás seguro de que deseas eliminar esta solicitud?',
    );

    if (!confirmar) {
        return;
    }

    router.delete(`/solicitudes/${id}`);
};
</script>

<template>
    <main class="min-h-screen bg-[#F8F5F3] p-4 md:p-8">
        <!-- ENCABEZADO -->
        <section
            class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <p
                    class="mb-2 text-xs font-bold tracking-widest text-[#7A1631] uppercase"
                >
                    CampusSOS
                </p>

                <h1
                    class="text-2xl font-bold text-[#30171E] md:text-3xl"
                >
                    Mis SOS
                </h1>

                <p class="mt-2 text-sm text-[#6F6266]">
                    Administra las solicitudes de ayuda que has publicado.
                </p>
            </div>

            <Link
                href="/solicitudes/create"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#7A1631] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#5E1025]"
            >
                <Plus class="size-4" />
                Publicar SOS
            </Link>
        </section>

        <!-- SIN SOLICITUDES -->
        <section
            v-if="solicitudes.length === 0"
            class="rounded-2xl border border-[#E8DFE1] bg-white p-10 text-center shadow-sm"
        >
            <div
                class="mx-auto mb-4 flex size-12 items-center justify-center rounded-full bg-[#F4E8EC] text-[#7A1631]"
            >
                <Plus class="size-6" />
            </div>

            <h2 class="text-lg font-bold text-[#30171E]">
                Aún no tienes solicitudes
            </h2>

            <p class="mt-2 text-sm text-[#6F6266]">
                Publica tu primer SOS para solicitar ayuda a la comunidad.
            </p>

            <Link
                href="/solicitudes/create"
                class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#7A1631] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#5E1025]"
            >
                <Plus class="size-4" />
                Crear solicitud
            </Link>
        </section>

        <!-- LISTADO DE SOLICITUDES -->
        <section
            v-else
            class="grid grid-cols-1 gap-5 lg:grid-cols-2"
        >
            <article
                v-for="solicitud in solicitudes"
                :key="solicitud.id"
                class="rounded-2xl border border-[#E8DFE1] bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
            >
                <!-- PARTE SUPERIOR -->
                <div
                    class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between"
                >
                    <div>
                        <div class="mb-2 flex flex-wrap gap-2">
                            <!-- ESTADO -->
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                :class="{
                                    'bg-green-100 text-green-700':
                                        solicitud.estado_temporal === 'activa',

                                    'bg-blue-100 text-blue-700':
                                        solicitud.estado_temporal ===
                                        'programada',

                                    'bg-gray-100 text-gray-600':
                                        solicitud.estado_temporal === 'expirada',
                                }"
                            >
                                {{ solicitud.estado_temporal }}
                            </span>

                            <!-- URGENCIA -->
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                :class="{
                                    'bg-red-100 text-red-700':
                                        solicitud.urgencia === 'alta',

                                    'bg-yellow-100 text-yellow-700':
                                        solicitud.urgencia === 'media',

                                    'bg-green-100 text-green-700':
                                        solicitud.urgencia === 'baja',
                                }"
                            >
                                Urgencia {{ solicitud.urgencia }}
                            </span>
                        </div>

                        <h2
                            class="text-lg font-bold text-[#30171E]"
                        >
                            {{ solicitud.titulo }}
                        </h2>

                        <p
                            class="mt-1 text-xs font-semibold text-[#7A1631]"
                        >
                            {{ solicitud.categoria }}
                        </p>
                    </div>
                </div>

                <!-- DESCRIPCIÓN -->
                <p
                    class="mb-5 text-sm leading-relaxed text-[#6F6266]"
                >
                    {{ solicitud.descripcion }}
                </p>

                <!-- INFORMACIÓN -->
                <div
                    class="mb-5 space-y-3 border-t border-[#E8DFE1] pt-4"
                >
                    <div
                        class="flex items-center gap-2 text-sm text-[#6F6266]"
                    >
                        <MapPin
                            class="size-4 shrink-0 text-[#7A1631]"
                        />

                        <span>
                            {{ solicitud.ubicacion }}
                        </span>
                    </div>

                    <div
                        class="flex items-center gap-2 text-sm text-[#6F6266]"
                    >
                        <CalendarDays
                            class="size-4 shrink-0 text-[#7A1631]"
                        />

                        <span>
                            Inicia:
                            {{ formatearFecha(solicitud.inicia_en) }}
                        </span>
                    </div>

                    <div
                        class="flex items-center gap-2 text-sm text-[#6F6266]"
                    >
                        <Clock
                            class="size-4 shrink-0 text-[#7A1631]"
                        />

                        <span>
                            Expira:
                            {{ formatearFecha(solicitud.expira_en) }}
                        </span>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div
                    class="flex flex-col gap-2 border-t border-[#E8DFE1] pt-4 sm:flex-row sm:justify-end"
                >
                    <Link
                        v-if="
                            solicitud.estado_temporal !== 'expirada'
                        "
                        :href="`/solicitudes/${solicitud.id}/edit`"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-[#7A1631]/20 px-4 py-2 text-sm font-semibold text-[#7A1631] transition hover:bg-[#F4E8EC]"
                    >
                        <Pencil class="size-4" />
                        Editar
                    </Link>

                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                        @click="eliminarSolicitud(solicitud.id)"
                    >
                        <Trash2 class="size-4" />
                        Eliminar
                    </button>
                </div>

                <!-- MENSAJE EXPIRADA -->
                <p
                    v-if="solicitud.estado_temporal === 'expirada'"
                    class="mt-3 text-right text-xs text-[#6F6266]"
                >
                    Esta solicitud ya expiró y no puede editarse.
                </p>
            </article>
        </section>
    </main>
</template>