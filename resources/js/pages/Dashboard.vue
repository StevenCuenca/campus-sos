<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    CalendarDays,
    Clock3,
    MapPin,
    Plus,
    Radio,
    Siren,
} from '@lucide/vue';

interface Estadisticas {
    total: number;
    activas: number;
    programadas: number;
    expiradas: number;
}

interface Solicitud {
    id: number;
    titulo: string;
    ubicacion: string;
    urgencia: string;
    estado_temporal: 'programada' | 'activa' | 'expirada';
    inicia_en: string | null;
    expira_en: string | null;
}

const props = defineProps<{
    estadisticas: Estadisticas;
    recientes: Solicitud[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Inicio',
                href: '/dashboard',
            },
        ],
    },
});

const activas = () =>
    props.recientes.filter(
        (solicitud) => solicitud.estado_temporal === 'activa',
    );

const programadas = () =>
    props.recientes.filter(
        (solicitud) => solicitud.estado_temporal === 'programada',
    );

const formatearHora = (fecha: string | null) => {
    if (!fecha) return '--:--';

    return new Date(fecha).toLocaleTimeString('es-EC', {
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatearDia = (fecha: string | null) => {
    if (!fecha) return 'Sin fecha';

    return new Date(fecha)
        .toLocaleDateString('es-EC', {
            day: '2-digit',
            month: 'short',
        })
        .toUpperCase();
};

const tiempoRestante = (fecha: string | null) => {
    if (!fecha) return 'Sin límite';

    const diferencia =
        new Date(fecha).getTime() - new Date().getTime();

    if (diferencia <= 0) {
        return 'Expirada';
    }

    const minutos = Math.floor(diferencia / 60000);

    if (minutos < 60) {
        return `${minutos} min`;
    }

    const horas = Math.floor(minutos / 60);
    const resto = minutos % 60;

    if (horas < 24) {
        return resto > 0
            ? `${horas}h ${resto}m`
            : `${horas}h`;
    }

    const dias = Math.floor(horas / 24);

    return `${dias} día${dias > 1 ? 's' : ''}`;
};

const anchoTiempo = (solicitud: Solicitud) => {
    if (!solicitud.inicia_en || !solicitud.expira_en) {
        return '100%';
    }

    const inicio = new Date(solicitud.inicia_en).getTime();
    const fin = new Date(solicitud.expira_en).getTime();
    const ahora = new Date().getTime();

    if (ahora <= inicio) return '100%';
    if (ahora >= fin) return '0%';

    const total = fin - inicio;
    const restante = fin - ahora;

    return `${Math.max(0, Math.min(100, (restante / total) * 100))}%`;
};
</script>

<template>
    <Head title="CampusSOS" />

    <main class="min-h-full bg-[#FBF8F6] text-[#321C22]">

        <!-- CABECERA -->
        <div class="px-6 pb-5 pt-8 md:px-10">
            <div
                class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between"
            >
                <div>
                    <div
                        class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.22em] text-[#9A5365]"
                    >
                        <span
                            class="inline-block size-2 rounded-full bg-[#8A1835]"
                        ></span>
                        Campus activo
                    </div>

                    <h1
                        class="max-w-2xl text-3xl font-semibold tracking-[-0.03em] text-[#431923] md:text-4xl"
                    >
                        El pulso de tu campus.
                    </h1>

                    <p
                        class="mt-2 max-w-xl text-sm leading-6 text-[#80656C]"
                    >
                        Tus solicitudes, su tiempo y lo que necesita
                        atención ahora mismo.
                    </p>
                </div>

                <Link
                    href="/solicitudes/create"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-[#861832] px-5 py-3 text-sm font-semibold text-white shadow-[0_8px_24px_rgba(134,24,50,0.18)] transition hover:-translate-y-0.5 hover:bg-[#701229]"
                >
                    <Plus class="size-4" />
                    Nuevo SOS
                </Link>
            </div>
        </div>

        <!-- LÍNEA DECORATIVA -->
        <div class="px-6 md:px-10">
            <div class="h-px bg-[#EADDE0]"></div>
        </div>

        <!-- GRID PRINCIPAL -->
        <div
            class="grid gap-5 px-6 py-7 md:px-10 xl:grid-cols-[minmax(0,1.65fr)_minmax(280px,0.75fr)]"
        >

            <!-- CAMPUS PULSE -->
            <section
                class="relative overflow-hidden rounded-[28px] bg-[#7D1730] p-6 text-white shadow-[0_18px_50px_rgba(82,19,35,0.14)] md:p-8"
            >
                <!-- Decoración -->
                <div
                    class="pointer-events-none absolute -right-20 -top-24 size-72 rounded-full border border-white/10"
                ></div>

                <div
                    class="pointer-events-none absolute -right-5 -top-5 size-40 rounded-full border border-white/10"
                ></div>

                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Radio class="size-4 text-[#F6B8C7]" />

                            <span
                                class="text-xs font-bold uppercase tracking-[0.2em] text-[#F6CFD8]"
                            >
                                Campus Pulse
                            </span>
                        </div>

                        <span
                            class="rounded-full bg-white/10 px-3 py-1 text-xs text-white/80"
                        >
                            En vivo
                        </span>
                    </div>

                    <!-- SI HAY SOS ACTIVOS -->
                    <div
                        v-if="activas().length > 0"
                        class="mt-10"
                    >
                        <p
                            class="text-sm font-medium text-white/60"
                        >
                            Necesita atención ahora
                        </p>

                        <h2
                            class="mt-2 max-w-xl text-3xl font-semibold tracking-[-0.03em] md:text-4xl"
                        >
                            {{ activas()[0].titulo }}
                        </h2>

                        <div
                            class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-white/70"
                        >
                            <span class="flex items-center gap-2">
                                <MapPin class="size-4" />
                                {{ activas()[0].ubicacion }}
                            </span>

                            <span class="capitalize">
                                Prioridad {{ activas()[0].urgencia }}
                            </span>
                        </div>

                        <!-- TIEMPO -->
                        <div
                            class="mt-10 grid gap-6 md:grid-cols-[auto_1fr] md:items-end"
                        >
                            <div>
                                <p
                                    class="text-[11px] font-bold uppercase tracking-[0.2em] text-white/50"
                                >
                                    Tiempo restante
                                </p>

                                <p
                                    class="mt-1 text-4xl font-semibold tracking-tight"
                                >
                                    {{
                                        tiempoRestante(
                                            activas()[0].expira_en
                                        )
                                    }}
                                </p>
                            </div>

                            <div class="pb-2">
                                <div
                                    class="h-1.5 overflow-hidden rounded-full bg-black/20"
                                >
                                    <div
                                        class="h-full rounded-full bg-[#F5C1CD] transition-all"
                                        :style="{
                                            width: anchoTiempo(
                                                activas()[0]
                                            ),
                                        }"
                                    ></div>
                                </div>

                                <div
                                    class="mt-2 flex justify-between text-[11px] text-white/45"
                                >
                                    <span>
                                        {{
                                            formatearHora(
                                                activas()[0].inicia_en
                                            )
                                        }}
                                    </span>

                                    <span>
                                        {{
                                            formatearHora(
                                                activas()[0].expira_en
                                            )
                                        }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SIN ACTIVOS -->
                    <div
                        v-else
                        class="mt-12 max-w-lg"
                    >
                        <p class="text-5xl font-semibold">
                            Todo tranquilo.
                        </p>

                        <p
                            class="mt-4 text-sm leading-6 text-white/65"
                        >
                            No tienes ningún SOS activo en este momento.
                            Puedes programar uno o publicar una necesidad
                            inmediata.
                        </p>

                        <Link
                            href="/solicitudes/create"
                            class="mt-7 inline-flex items-center gap-2 text-sm font-semibold"
                        >
                            Publicar ahora
                            <ArrowRight class="size-4" />
                        </Link>
                    </div>
                </div>
            </section>

            <!-- ACTIVIDAD -->
            <aside
                class="rounded-[28px] border border-[#E9DDE0] bg-white p-6"
            >
                <p
                    class="text-xs font-bold uppercase tracking-[0.18em] text-[#A06A78]"
                >
                    Tu actividad
                </p>

                <div class="mt-7">
                    <span
                        class="text-6xl font-semibold tracking-[-0.06em] text-[#66172B]"
                    >
                        {{ estadisticas.total.toString().padStart(2, '0') }}
                    </span>

                    <p class="mt-2 text-sm text-[#82666E]">
                        SOS publicados
                    </p>
                </div>

                <div class="my-7 h-px bg-[#EEE4E6]"></div>

                <div class="space-y-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span
                                class="size-2 rounded-full bg-[#8B1736]"
                            ></span>
                            <span class="text-sm text-[#664850]">
                                Activos
                            </span>
                        </div>

                        <strong class="text-[#431923]">
                            {{ estadisticas.activas }}
                        </strong>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span
                                class="size-2 rounded-full bg-[#D29AAA]"
                            ></span>
                            <span class="text-sm text-[#664850]">
                                Programados
                            </span>
                        </div>

                        <strong class="text-[#431923]">
                            {{ estadisticas.programadas }}
                        </strong>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span
                                class="size-2 rounded-full bg-[#D8D0D2]"
                            ></span>
                            <span class="text-sm text-[#664850]">
                                Expirados
                            </span>
                        </div>

                        <strong class="text-[#431923]">
                            {{ estadisticas.expiradas }}
                        </strong>
                    </div>
                </div>

                <Link
                    href="/solicitudes"
                    class="mt-8 flex items-center justify-between rounded-2xl bg-[#F8F1F3] px-4 py-3 text-sm font-semibold text-[#781A32] transition hover:bg-[#F1E2E6]"
                >
                    Ver mi historial
                    <ArrowRight class="size-4" />
                </Link>
            </aside>
        </div>

        <!-- PRÓXIMAMENTE -->
        <section class="px-6 pb-10 md:px-10">
            <div
                class="grid gap-5 xl:grid-cols-[minmax(0,1.65fr)_minmax(280px,0.75fr)]"
            >
                <div
                    class="rounded-[28px] border border-[#E9DDE0] bg-white p-6 md:p-8"
                >
                    <div
                        class="flex items-center justify-between"
                    >
                        <div>
                            <p
                                class="text-xs font-bold uppercase tracking-[0.18em] text-[#A06A78]"
                            >
                                Próximamente
                            </p>

                            <h2
                                class="mt-2 text-xl font-semibold text-[#431923]"
                            >
                                Tu agenda SOS
                            </h2>
                        </div>

                        <CalendarDays
                            class="size-5 text-[#8B1736]"
                        />
                    </div>

                    <!-- TIMELINE -->
                    <div
                        v-if="programadas().length > 0"
                        class="mt-8"
                    >
                        <div
                            v-for="solicitud in programadas()"
                            :key="solicitud.id"
                            class="grid grid-cols-[62px_20px_1fr] gap-3"
                        >
                            <div class="pt-1 text-right">
                                <p
                                    class="text-xs font-bold text-[#9A6472]"
                                >
                                    {{
                                        formatearDia(
                                            solicitud.inicia_en
                                        )
                                    }}
                                </p>

                                <p
                                    class="mt-1 text-sm font-semibold text-[#4E2832]"
                                >
                                    {{
                                        formatearHora(
                                            solicitud.inicia_en
                                        )
                                    }}
                                </p>
                            </div>

                            <div class="flex flex-col items-center">
                                <span
                                    class="mt-1.5 size-3 rounded-full border-[3px] border-[#F4DDE3] bg-[#8B1736]"
                                ></span>

                                <div
                                    class="min-h-16 w-px flex-1 bg-[#EADDE0]"
                                ></div>
                            </div>

                            <div class="pb-7">
                                <p
                                    class="font-semibold text-[#431923]"
                                >
                                    {{ solicitud.titulo }}
                                </p>

                                <p
                                    class="mt-1 flex items-center gap-1.5 text-sm text-[#846971]"
                                >
                                    <MapPin class="size-3.5" />
                                    {{ solicitud.ubicacion }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="mt-8 rounded-2xl bg-[#FBF6F7] px-5 py-7"
                    >
                        <p class="text-sm font-medium text-[#654650]">
                            No tienes SOS programados.
                        </p>

                        <p class="mt-1 text-xs text-[#967982]">
                            Cuando programes uno, aparecerá aquí
                            como parte de tu agenda.
                        </p>
                    </div>
                </div>

                <!-- NUEVO SOS -->
                <Link
                    href="/solicitudes/create"
                    class="group relative flex min-h-64 flex-col justify-between overflow-hidden rounded-[28px] bg-[#EEDDE1] p-7 transition hover:-translate-y-1"
                >
                    <div
                        class="absolute -right-16 -top-16 size-52 rounded-full border border-[#CDA8B2]/50"
                    ></div>

                    <div
                        class="relative flex size-12 items-center justify-center rounded-full bg-[#861832] text-white"
                    >
                        <Plus class="size-5" />
                    </div>

                    <div class="relative">
                        <p
                            class="text-xs font-bold uppercase tracking-[0.18em] text-[#9B5D6D]"
                        >
                            ¿Necesitas algo?
                        </p>

                        <h3
                            class="mt-2 text-2xl font-semibold tracking-tight text-[#531A29]"
                        >
                            Lanza un SOS.
                        </h3>

                        <p
                            class="mt-2 max-w-xs text-sm leading-6 text-[#80616A]"
                        >
                            Dile a tu comunidad qué necesitas y durante
                            cuánto tiempo.
                        </p>

                        <div
                            class="mt-5 flex items-center gap-2 text-sm font-bold text-[#7C1832]"
                        >
                            Crear solicitud
                            <ArrowRight
                                class="size-4 transition group-hover:translate-x-1"
                            />
                        </div>
                    </div>
                </Link>
            </div>
        </section>
    </main>
</template>