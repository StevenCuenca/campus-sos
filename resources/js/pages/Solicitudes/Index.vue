<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';

interface Solicitud {
    id: number;
    titulo: string;
    descripcion: string;
    categoria: string;
    ubicacion: string;
    urgencia: string;
    estado_temporal: 'programada' | 'activa' | 'expirada';
    inicia_en: string | null;
    expira_en: string | null;
}

defineProps<{
    solicitudes: Solicitud[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Solicitudes',
                href: '/solicitudes',
            },
        ],
    },
});

const eliminarSolicitud = (id: number) => {
    if (confirm('¿Estás seguro de eliminar esta solicitud?')) {
        router.delete(`/solicitudes/${id}`);
    }
};

const textoEstado = (estado: string) => {
    if (estado === 'programada') return '📅 Programada';
    if (estado === 'expirada') return '⏱ Expirada';

    return '🟢 Activa';
};

const claseEstado = (estado: string) => {
    if (estado === 'programada') {
        return 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300';
    }

    if (estado === 'expirada') {
        return 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300';
    }

    return 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-300';
};

const claseUrgencia = (urgencia: string) => {
    if (urgencia === 'alta') {
        return 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300';
    }

    if (urgencia === 'media') {
        return 'bg-yellow-100 text-yellow-700 dark:bg-yellow-950 dark:text-yellow-300';
    }

    return 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-300';
};

const formatearFecha = (fecha: string | null) => {
    if (!fecha) return 'Sin definir';

    return new Date(fecha).toLocaleString('es-EC', {
        dateStyle: 'short',
        timeStyle: 'short',
    });
};
</script>

<template>
    <Head title="Mis Solicitudes" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4">
        <!-- ENCABEZADO -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Mis SOS</h1>

                <p class="text-sm text-muted-foreground">
                    Administra tus solicitudes de ayuda.
                </p>
            </div>

            <Link
                href="/solicitudes/create"
                class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
            >
                + Publicar SOS
            </Link>
        </div>

        <!-- SIN SOLICITUDES -->
        <div
            v-if="solicitudes.length === 0"
            class="rounded-2xl border p-10 text-center"
        >
            <div class="text-4xl">🆘</div>

            <h2 class="mt-3 text-lg font-semibold">
                Todavía no tienes solicitudes
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Publica tu primer SOS y pide ayuda a la comunidad.
            </p>
        </div>

        <!-- SOLICITUDES -->
        <div v-else class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article
                v-for="solicitud in solicitudes"
                :key="solicitud.id"
                class="rounded-2xl border p-5 shadow-sm transition hover:shadow-md"
            >
                <!-- BADGES -->
                <div class="mb-4 flex items-center justify-between gap-2">
                    <span
                        class="rounded-full px-3 py-1 text-xs font-semibold"
                        :class="claseEstado(solicitud.estado_temporal)"
                    >
                        {{ textoEstado(solicitud.estado_temporal) }}
                    </span>

                    <span
                        class="rounded-full px-3 py-1 text-xs font-semibold capitalize"
                        :class="claseUrgencia(solicitud.urgencia)"
                    >
                        {{ solicitud.urgencia }}
                    </span>
                </div>

                <!-- INFORMACIÓN -->
                <h2 class="text-lg font-bold">
                    {{ solicitud.titulo }}
                </h2>

                <p class="mt-2 text-sm text-muted-foreground">
                    {{ solicitud.descripcion }}
                </p>

                <div class="mt-5 space-y-2 text-sm">
                    <p>
                        📂
                        <span class="font-medium">
                            {{ solicitud.categoria }}
                        </span>
                    </p>

                    <p>📍 {{ solicitud.ubicacion }}</p>

                    <p>
                        🕐 Inicio:
                        {{ formatearFecha(solicitud.inicia_en) }}
                    </p>

                    <p>
                        ⏳ Fin:
                        {{ formatearFecha(solicitud.expira_en) }}
                    </p>
                </div>

                <!-- ACCIONES -->
                <div class="mt-5 flex gap-2 border-t pt-4">
                    <!-- SOLO EDITABLE SI NO EXPIRÓ -->
                    <Link
                        v-if="solicitud.estado_temporal !== 'expirada'"
                        :href="`/solicitudes/${solicitud.id}/edit`"
                        class="rounded-lg border px-3 py-2 text-sm font-medium"
                    >
                        ✏️ Editar
                    </Link>

                    <span
                        v-else
                        class="cursor-not-allowed rounded-lg border px-3 py-2 text-sm text-muted-foreground"
                    >
                        🔒 No editable
                    </span>

                    <button
                        type="button"
                        class="rounded-lg border px-3 py-2 text-sm font-medium"
                        @click="eliminarSolicitud(solicitud.id)"
                    >
                        🗑 Eliminar
                    </button>
                </div>
            </article>
        </div>
    </div>
</template>
