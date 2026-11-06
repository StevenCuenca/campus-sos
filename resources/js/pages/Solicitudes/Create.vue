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

    <main class="min-h-screen bg-[#F8F5F3] p-4 md:p-8">
        <div class="mx-auto w-full max-w-4xl">

            <!-- ENCABEZADO -->
            <section class="mb-8">
                <p
                    class="mb-2 text-xs font-bold tracking-widest text-[#7A1631] uppercase"
                >
                    CAMPUSSOS
                </p>

                <h1
                    class="text-2xl font-bold text-[#30171E] md:text-3xl"
                >
                    Publicar un SOS
                </h1>

                <p class="mt-2 text-sm text-[#6F6266]">
                    Cuéntale a la comunidad universitaria qué necesitas.
                </p>
            </section>

            <!-- FORMULARIO -->
            <form
                class="space-y-6 rounded-2xl border border-[#E8DFE1] bg-white p-5 shadow-sm md:p-7"
                @submit.prevent="publicar"
            >
                <!-- TÍTULO -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-[#30171E]"
                    >
                        ¿Qué necesitas?
                    </label>

                    <input
                        v-model="form.titulo"
                        type="text"
                        placeholder="Ej: Necesito un cargador USB-C"
                        class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition placeholder:text-[#9B8F93] focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
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
                    <label
                        class="mb-2 block text-sm font-semibold text-[#30171E]"
                    >
                        Descripción
                    </label>

                    <textarea
                        v-model="form.descripcion"
                        rows="4"
                        placeholder="Explica brevemente cómo pueden ayudarte..."
                        class="w-full resize-none rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition placeholder:text-[#9B8F93] focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
                    ></textarea>

                    <p
                        v-if="form.errors.descripcion"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.descripcion }}
                    </p>
                </div>

                <!-- CATEGORÍA + UBICACIÓN -->
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-[#30171E]"
                        >
                            Categoría
                        </label>

                        <select
                            v-model="form.categoria"
                            class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none focus:border-[#7A1631]"
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

                    <div>
                        <label
                            class="mb-2 block text-sm font-semibold text-[#30171E]"
                        >
                            ¿Dónde necesitas ayuda?
                        </label>

                        <input
                            v-model="form.ubicacion"
                            type="text"
                            placeholder="Ej: Biblioteca"
                            class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition placeholder:text-[#9B8F93] focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
                        />

                        <p
                            v-if="form.errors.ubicacion"
                            class="mt-1 text-sm text-red-500"
                        >
                            {{ form.errors.ubicacion }}
                        </p>
                    </div>
                </div>

                <!-- URGENCIA -->
                <div>
                    <label
                        class="mb-2 block text-sm font-semibold text-[#30171E]"
                    >
                        Nivel de urgencia
                    </label>

                    <select
                        v-model="form.urgencia"
                        class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none focus:border-[#7A1631]"
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
                <section class="border-t border-[#E8DFE1] pt-6">
                    <div class="mb-5">
                        <h2
                            class="text-lg font-bold text-[#30171E]"
                        >
                            ¿Cuándo necesitas ayuda?
                        </h2>

                        <p class="mt-1 text-sm text-[#6F6266]">
                            Define durante cuánto tiempo estará vigente tu SOS.
                        </p>
                    </div>

                    <!-- MODO -->
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <button
                            type="button"
                            class="rounded-xl border p-4 text-left transition"
                            :class="
                                modoTiempo === 'ahora'
                                    ? 'border-[#7A1631] bg-[#F8EDF0]'
                                    : 'border-[#E8DFE1] bg-white hover:bg-[#FAF7F6]'
                            "
                            @click="modoTiempo = 'ahora'"
                        >
                            <div
                                class="font-semibold text-[#30171E]"
                            >
                                ⚡ Lo necesito ahora
                            </div>

                            <div
                                class="mt-1 text-xs text-[#6F6266]"
                            >
                                La solicitud comienza inmediatamente.
                            </div>
                        </button>

                        <button
                            type="button"
                            class="rounded-xl border p-4 text-left transition"
                            :class="
                                modoTiempo === 'programar'
                                    ? 'border-[#7A1631] bg-[#F8EDF0]'
                                    : 'border-[#E8DFE1] bg-white hover:bg-[#FAF7F6]'
                            "
                            @click="modoTiempo = 'programar'"
                        >
                            <div
                                class="font-semibold text-[#30171E]"
                            >
                                📅 Programar
                            </div>

                            <div
                                class="mt-1 text-xs text-[#6F6266]"
                            >
                                Necesitaré ayuda más tarde.
                            </div>
                        </button>
                    </div>

                    <!-- AHORA -->
                    <div
                        v-if="modoTiempo === 'ahora'"
                        class="mt-4 rounded-xl border border-[#E8DFE1] bg-[#FAF7F6] p-4"
                    >
                        <p
                            class="mb-3 text-sm font-semibold text-[#30171E]"
                        >
                            ¿Durante cuánto tiempo?
                        </p>

                        <div
                            class="grid grid-cols-2 gap-2 sm:grid-cols-4"
                        >
                            <button
                                v-for="opcion in [
                                    {
                                        texto: '30 min',
                                        minutos: 30,
                                    },
                                    {
                                        texto: '1 hora',
                                        minutos: 60,
                                    },
                                    {
                                        texto: '2 horas',
                                        minutos: 120,
                                    },
                                    {
                                        texto: '4 horas',
                                        minutos: 240,
                                    },
                                ]"
                                :key="opcion.minutos"
                                type="button"
                                class="rounded-lg border px-3 py-3 text-sm font-semibold transition"
                                :class="
                                    duracion === opcion.minutos
                                        ? 'border-[#7A1631] bg-[#7A1631] text-white'
                                        : 'border-[#E8DFE1] bg-white text-[#30171E] hover:bg-[#F4E8EC]'
                                "
                                @click="
                                    seleccionarDuracion(
                                        opcion.minutos,
                                    )
                                "
                            >
                                {{ opcion.texto }}
                            </button>
                        </div>

                        <p class="mt-3 text-xs text-[#6F6266]">
                            ⚡ Tu SOS comenzará al momento de publicarlo.
                        </p>
                    </div>

                    <!-- PROGRAMAR -->
                    <div
                        v-if="modoTiempo === 'programar'"
                        class="mt-4 grid gap-4 rounded-xl border border-[#E8DFE1] bg-[#FAF7F6] p-4 md:grid-cols-2"
                    >
                        <div>
                            <label
                                class="mb-2 block text-sm font-semibold text-[#30171E]"
                            >
                                Desde
                            </label>

                            <input
                                v-model="form.inicia_en"
                                type="datetime-local"
                                class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none focus:border-[#7A1631]"
                            />

                            <p
                                v-if="form.errors.inicia_en"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.inicia_en }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-semibold text-[#30171E]"
                            >
                                Hasta
                            </label>

                            <input
                                v-model="form.expira_en"
                                type="datetime-local"
                                class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none focus:border-[#7A1631]"
                            />

                            <p
                                v-if="form.errors.expira_en"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.expira_en }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- BOTONES -->
                <div
                    class="flex flex-col-reverse gap-3 border-t border-[#E8DFE1] pt-5 sm:flex-row sm:justify-end"
                >
                    <Link
                        href="/solicitudes"
                        class="rounded-xl border border-[#E8DFE1] bg-white px-5 py-3 text-center text-sm font-semibold text-[#6F6266] transition hover:bg-[#FAF7F6]"
                    >
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-xl bg-[#7A1631] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#5E1025] disabled:cursor-not-allowed disabled:opacity-50"
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
    </main>
</template>