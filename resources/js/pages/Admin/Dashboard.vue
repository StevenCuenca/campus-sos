
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Administración CampusSOS" />

    <main class="min-h-screen bg-[#F8F5F3] p-6 md:p-8">
        <div class="mx-auto max-w-6xl">
            <p class="mb-2 text-xs font-bold tracking-widest text-[#7A1631]">
                CAMPUSSOS / ADMINISTRACIÓN
            </p>

            <h1 class="text-3xl font-bold text-[#30171E]">
                Panel de administración
            </h1>

            <p class="mt-2 text-[#6F6266]">
                Gestiona la información que alimentará el Core de Colaboración.
            </p>

            <section class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-3">
                <article class="rounded-2xl border border-[#E8DFE1] bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-[#30171E]">
                        Facultades
                    </h2>
                    <p class="mt-2 text-sm text-[#6F6266]">
                        Administrar facultades y estudiantes matriculados.
                    </p>
                </article>

                <article class="rounded-2xl border border-[#E8DFE1] bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-[#30171E]">
                        Estudiantes
                    </h2>
                    <p class="mt-2 text-sm text-[#6F6266]">
                        Gestionar la asignación de estudiantes a facultades.
                    </p>
                </article>

                <article class="rounded-2xl border border-[#E8DFE1] bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-[#30171E]">
                        Core de Colaboración
                    </h2>
                    <p class="mt-2 text-sm text-[#6F6266]">
                        Datos utilizados para calcular el ranking interfacultades.
                    </p>
                </article>
            </section>
        </div>
    </main>
</template>
