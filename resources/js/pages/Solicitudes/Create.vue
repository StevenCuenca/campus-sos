<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

type ModoTiempo = 'ahora' | 'programar';

const modoTiempo = ref<ModoTiempo>('ahora');
const duracion = ref(60);

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Solicitudes',
                href: '/solicitudes',
            },
            {
                title: 'Publicar SOS',
                href: '/solicitudes/create',
            },
        ],
    },
});

const form = useForm({
    titulo: '',
    descripcion: '',
    categoria: '',
    ubicacion: '',
    urgencia: 'media',
    inicia_en: '',
    expira_en: '',
});

// Convierte una fecha de JavaScript al formato que enviaremos a Laravel
const formatearFecha = (fecha: Date) => {
    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, '0');
    const day = String(fecha.getDate()).padStart(2, '0');
    const hours = String(fecha.getHours()).padStart(2, '0');
    const minutes = String(fecha.getMinutes()).padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
};

const seleccionarDuracion = (minutos: number) => {
    duracion.value = minutos;
};

const publicar = () => {
    // Si el usuario seleccionó "Ahora",
    // calculamos automáticamente el inicio y el final.
    if (modoTiempo.value === 'ahora') {
        const inicio = new Date();
        const fin = new Date(
            inicio.getTime() + duracion.value * 60 * 1000,
        );

        form.inicia_en = formatearFecha(inicio);
        form.expira_en = formatearFecha(fin);
    }

    form.post('/solicitudes');
};
</script>

<template>
    <Head title="Publicar SOS" />

    <div class="mx-auto flex w-full max-w-3xl flex-col gap-6 p-4">

        <!-- Encabezado -->
        <div>
            <h1 class="text-2xl font-bold">
                Publicar un SOS
            </h1>

            <p class="mt-1 text-sm text-muted-foreground">
                Cuéntale a la comunidad universitaria qué necesitas.
            </p>
        </div>

        <form
            class="space-y-6 rounded-xl border p-6"
            @submit.prevent="publicar"
        >
            <!-- Título -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    ¿Qué necesitas?
                </label>

                <input
                    v-model="form.titulo"
                    type="text"
                    placeholder="Ej: Necesito un cargador USB-C"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                />

                <p
                    v-if="form.errors.titulo"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.titulo }}
                </p>
            </div>

            <!-- Descripción -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Descripción
                </label>

                <textarea
                    v-model="form.descripcion"
                    rows="4"
                    placeholder="Explica brevemente cómo pueden ayudarte..."
                    class="w-full rounded-lg border bg-background px-3 py-2"
                ></textarea>

                <p
                    v-if="form.errors.descripcion"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.descripcion }}
                </p>
            </div>

            <!-- Categoría -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Categoría
                </label>

                <select
                    v-model="form.categoria"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                >
                    <option value="" disabled>
                        Selecciona una categoría
                    </option>

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

            <!-- Ubicación -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    ¿Dónde necesitas ayuda?
                </label>

                <input
                    v-model="form.ubicacion"
                    type="text"
                    placeholder="Ej: Biblioteca"
                    class="w-full rounded-lg border bg-background px-3 py-2"
                />

                <p
                    v-if="form.errors.ubicacion"
                    class="mt-1 text-sm text-red-500"
                >
                    {{ form.errors.ubicacion }}
                </p>
            </div>

            <!-- Urgencia -->
            <div>
                <label class="mb-2 block text-sm font-medium">
                    Nivel de urgencia
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
            </div>

            <!-- TIEMPO -->
            <div class="space-y-4 border-t pt-6">
                <div>
                    <h2 class="font-semibold">
                        ¿Cuándo necesitas ayuda?
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Define durante cuánto tiempo estará vigente tu SOS.
                    </p>
                </div>

                <!-- Selección de modo -->
                <div class="grid grid-cols-2 gap-3">
                    <button
                        type="button"
                        class="rounded-xl border p-4 text-left"
                        :class="
                            modoTiempo === 'ahora'
                                ? 'border-primary bg-primary/10'
                                : ''
                        "
                        @click="modoTiempo = 'ahora'"
                    >
                        <div class="font-semibold">
                            ⚡ Lo necesito ahora
                        </div>

                        <div class="mt-1 text-xs text-muted-foreground">
                            La solicitud comienza inmediatamente.
                        </div>
                    </button>

                    <button
                        type="button"
                        class="rounded-xl border p-4 text-left"
                        :class="
                            modoTiempo === 'programar'
                                ? 'border-primary bg-primary/10'
                                : ''
                        "
                        @click="modoTiempo = 'programar'"
                    >
                        <div class="font-semibold">
                            📅 Programar
                        </div>

                        <div class="mt-1 text-xs text-muted-foreground">
                            Necesitaré ayuda más tarde.
                        </div>
                    </button>
                </div>

                <!-- AHORA -->
                <div
                    v-if="modoTiempo === 'ahora'"
                    class="rounded-xl border p-4"
                >
                    <p class="mb-3 text-sm font-medium">
                        ¿Durante cuánto tiempo?
                    </p>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <button
                            v-for="opcion in [
                                { texto: '30 min', minutos: 30 },
                                { texto: '1 hora', minutos: 60 },
                                { texto: '2 horas', minutos: 120 },
                                { texto: '4 horas', minutos: 240 },
                            ]"
                            :key="opcion.minutos"
                            type="button"
                            class="rounded-lg border px-3 py-3 text-sm"
                            :class="
                                duracion === opcion.minutos
                                    ? 'border-primary bg-primary text-primary-foreground'
                                    : ''
                            "
                            @click="seleccionarDuracion(opcion.minutos)"
                        >
                            {{ opcion.texto }}
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-muted-foreground">
                        ⚡ Tu SOS comenzará al momento de publicarlo.
                    </p>
                </div>

                <!-- PROGRAMAR -->
                <div
                    v-if="modoTiempo === 'programar'"
                    class="grid gap-4 rounded-xl border p-4 md:grid-cols-2"
                >
                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Desde
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

                    <div>
                        <label class="mb-2 block text-sm font-medium">
                            Hasta
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

            <!-- Botones -->
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
                            ? 'Publicando...'
                            : 'Publicar SOS'
                    }}
                </button>
            </div>
        </form>
    </div>
</template>