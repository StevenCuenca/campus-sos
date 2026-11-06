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
            {
                title: 'Solicitudes',
                href: '/solicitudes',
            },
            {
                title: 'Editar',
                href: '#',
            },
        ],
    },
});

// Convierte la fecha de Laravel al formato de datetime-local
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
    inicia_en: fechaParaInput(props.solicitud.inicia_en),
    expira_en: fechaParaInput(props.solicitud.expira_en),
});

const actualizar = () => {
    form.put(`/solicitudes/${props.solicitud.id}`);
};
</script>

<template>
    <Head title="Editar SOS" />

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
                    Editar SOS
                </h1>

                <p class="mt-2 text-sm text-[#6F6266]">
                    Actualiza la información de tu solicitud.
                </p>
            </section>

            <!-- FORMULARIO -->
            <form
                class="space-y-6 rounded-2xl border border-[#E8DFE1] bg-white p-5 shadow-sm md:p-7"
                @submit.prevent="actualizar"
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
                        class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
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
                        class="w-full resize-none rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
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

                    <!-- CATEGORÍA -->
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
                        <label
                            class="mb-2 block text-sm font-semibold text-[#30171E]"
                        >
                            ¿Dónde necesitas ayuda?
                        </label>

                        <input
                            v-model="form.ubicacion"
                            type="text"
                            class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
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

                    <p
                        v-if="form.errors.urgencia"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ form.errors.urgencia }}
                    </p>
                </div>

                <!-- HORARIO -->
                <section class="border-t border-[#E8DFE1] pt-6">
                    <div class="mb-5">
                        <h2
                            class="text-lg font-bold text-[#30171E]"
                        >
                            Horario del SOS
                        </h2>

                        <p class="mt-1 text-sm text-[#6F6266]">
                            Puedes modificar cuándo comienza y termina tu
                            solicitud.
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-1 gap-4 rounded-xl border border-[#E8DFE1] bg-[#FAF7F6] p-4 md:grid-cols-2"
                    >
                        <!-- INICIO -->
                        <div>
                            <label
                                class="mb-2 block text-sm font-semibold text-[#30171E]"
                            >
                                🕐 Desde
                            </label>

                            <input
                                v-model="form.inicia_en"
                                type="datetime-local"
                                class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
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
                            <label
                                class="mb-2 block text-sm font-semibold text-[#30171E]"
                            >
                                ⏳ Hasta
                            </label>

                            <input
                                v-model="form.expira_en"
                                type="datetime-local"
                                class="w-full rounded-xl border border-[#E8DFE1] bg-white px-4 py-3 text-[#30171E] outline-none transition focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/10"
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
                                ? 'Guardando...'
                                : 'Guardar cambios'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</template>