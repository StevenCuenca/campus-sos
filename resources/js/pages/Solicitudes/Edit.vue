<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Solicitud {
    id: number;
    titulo: string;
    descripcion: string;
    categoria: string;
    ubicacion: string;
    urgencia: string;
    inicia_en: string | null;
    expira_en: string | null;
}

const props = defineProps<{
    solicitud: Solicitud;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Solicitudes', href: '/solicitudes' },
            { title: 'Editar', href: '#' },
        ],
    },
});

// Convierte la fecha que viene de Laravel
// al formato que necesita datetime-local
const fechaParaInput = (fecha: string | null) => {
    if (!fecha) return '';

    const date = new Date(fecha);

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
};

const form = useForm({
    titulo: props.solicitud.titulo,
    descripcion: props.solicitud.descripcion,
    categoria: props.solicitud.categoria,
    ubicacion: props.solicitud.ubicacion,
    urgencia: props.solicitud.urgencia,

    // Ahora enviamos las DOS fechas
    inicia_en: fechaParaInput(props.solicitud.inicia_en),
    expira_en: fechaParaInput(props.solicitud.expira_en),
});

const actualizar = () => {
    form.put(`/solicitudes/${props.solicitud.id}`);
};
</script>

<template>
    <Head title="Editar Solicitud" />

    <div class="mx-auto flex w-full max-w-3xl flex-col gap-6 p-4">

        <div>
            <h1 class="text-2xl font-bold">
                Editar SOS
            </h1>

            <p class="mt-1 text-sm text-muted-foreground">
                Actualiza la información de tu solicitud.
            </p>
        </div>

        <form
            class="space-y-5 rounded-xl border p-6"
            @submit.prevent="actualizar"
        >

            <!-- TÍTULO -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    ¿Qué necesitas?
                </label>

                <input
                    v-model="form.titulo"
                    type="text"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                />

                <p
                    v-if="form.errors.titulo"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.titulo }}
                </p>
            </div>

            <!-- DESCRIPCIÓN -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Descripción
                </label>

                <textarea
                    v-model="form.descripcion"
                    rows="4"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                ></textarea>

                <p
                    v-if="form.errors.descripcion"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.descripcion }}
                </p>
            </div>

            <!-- CATEGORÍA -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Categoría
                </label>

                <select
                    v-model="form.categoria"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                >
                    <option value="prestamo">
                        Préstamo
                    </option>

                    <option value="academico">
                        Ayuda académica
                    </option>

                    <option value="tecnologia">
                        Tecnología
                    </option>

                    <option value="companero">
                        Buscar compañero
                    </option>

                    <option value="otro">
                        Otro
                    </option>
                </select>

                <p
                    v-if="form.errors.categoria"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.categoria }}
                </p>
            </div>

            <!-- UBICACIÓN -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Ubicación
                </label>

                <input
                    v-model="form.ubicacion"
                    type="text"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                />

                <p
                    v-if="form.errors.ubicacion"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.ubicacion }}
                </p>
            </div>

            <!-- URGENCIA -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Urgencia
                </label>

                <select
                    v-model="form.urgencia"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                >
                    <option value="baja">
                        🟢 Baja
                    </option>

                    <option value="media">
                        🟡 Media
                    </option>

                    <option value="alta">
                        🔴 Alta
                    </option>
                </select>

                <p
                    v-if="form.errors.urgencia"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.urgencia }}
                </p>
            </div>

            <!-- HORARIO -->
            <div class="border-t pt-5">
                <h2 class="mb-1 font-semibold">
                    Horario del SOS
                </h2>

                <p class="mb-4 text-sm text-muted-foreground">
                    Puedes modificar cuándo comienza y termina tu solicitud.
                </p>

                <div class="grid gap-4 md:grid-cols-2">

                    <!-- INICIO -->
                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            🕐 Desde
                        </label>

                        <input
                            v-model="form.inicia_en"
                            type="datetime-local"
                            class="w-full rounded-lg border bg-background px-3 py-2"
                        />

                        <p
                            v-if="form.errors.inicia_en"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.inicia_en }}
                        </p>
                    </div>

                    <!-- FIN -->
                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            ⏳ Hasta
                        </label>

                        <input
                            v-model="form.expira_en"
                            type="datetime-local"
                            class="w-full rounded-lg border bg-background px-3 py-2"
                        />

                        <p
                            v-if="form.errors.expira_en"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.expira_en }}
                        </p>
                    </div>

                </div>
            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3 border-t pt-5">

                <Link
                    href="/solicitudes"
                    class="rounded-lg border px-4 py-2 text-sm font-medium"
                >
                    Cancelar
                </Link>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'Guardando...'
                            : 'Guardar cambios'
                    }}
                </button>

            </div>

        </form>
    </div>
</template>