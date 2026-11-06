
<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

const form = useForm({
    nombre: '',
    total_estudiantes: '',
});

function guardar() {
    form.post('/admin/facultades', {
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Nueva facultad" />

        <main class="min-h-screen bg-[#F8F5F3] p-6 md:p-8">
            <div class="mx-auto max-w-3xl">

                <div class="mb-8">
                    <p class="mb-2 text-xs font-bold uppercase tracking-widest text-[#7A1631]">
                        CampusSOS / Administración
                    </p>

                    <h1 class="text-3xl font-bold text-[#30171E]">
                        Registrar facultad
                    </h1>

                    <p class="mt-2 text-[#6F6266]">
                        Agrega una facultad al Core de Colaboración.
                    </p>
                </div>

                <form
                    @submit.prevent="guardar"
                    class="rounded-2xl border border-[#E9DEE1] bg-white p-6 shadow-sm md:p-8"
                >
                    <div class="mb-6">
                        <label
                            for="nombre"
                            class="mb-2 block font-semibold text-[#30171E]"
                        >
                            Nombre de la facultad
                        </label>

                        <input
                            id="nombre"
                            v-model="form.nombre"
                            type="text"
                            placeholder="Ej. Facultad de Ingeniería"
                            class="w-full rounded-xl border border-[#D9CDD0] px-4 py-3 outline-none focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/15"
                        />

                        <p
                            v-if="form.errors.nombre"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.nombre }}
                        </p>
                    </div>

                    <div class="mb-8">
                        <label
                            for="total_estudiantes"
                            class="mb-2 block font-semibold text-[#30171E]"
                        >
                            Total de estudiantes matriculados
                        </label>

                        <input
                            id="total_estudiantes"
                            v-model="form.total_estudiantes"
                            type="number"
                            min="1"
                            step="1"
                            placeholder="Ej. 1200"
                            class="w-full rounded-xl border border-[#D9CDD0] px-4 py-3 outline-none focus:border-[#7A1631] focus:ring-2 focus:ring-[#7A1631]/15"
                        />

                        <p class="mt-2 text-sm text-[#786C70]">
                            Este valor se utilizará para calcular la
                            participación proporcional de la facultad.
                        </p>

                        <p
                            v-if="form.errors.total_estudiantes"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ form.errors.total_estudiantes }}
                        </p>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3">
                        <Link
                            href="/admin/facultades"
                            class="rounded-xl border border-[#D9CDD0] px-5 py-3 font-semibold text-[#574A4E] hover:bg-[#F9F4F5]"
                        >
                            Cancelar
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-xl bg-[#7A1631] px-6 py-3 font-semibold text-white transition hover:bg-[#5D1025] disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {{
                                form.processing
                                    ? 'Guardando...'
                                    : 'Guardar facultad'
                            }}
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </AppLayout>
</template>
