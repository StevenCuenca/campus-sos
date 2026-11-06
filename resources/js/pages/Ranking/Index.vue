
<script setup lang="ts">
import { computed } from 'vue';

interface Facultad {
    id: number;
    posicion: number;
    nombre: string;
    participantes: number;
    total_estudiantes: number;
    participacion: number;
}

const props = defineProps<{
    facultades: Facultad[];
    totalParticipantes: number;
    participacionPromedio: number;
}>();

const facultadLider = computed(
    () => props.facultades[0]?.nombre ?? 'Sin datos'
);

function porcentaje(valor: number): string {
    return valor.toLocaleString('es-EC', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    });
}

function numero(valor: number): string {
    return valor.toLocaleString('es-EC');
}

function anchoBarra(valor: number): string {
    return `${Math.min(100, Math.max(0, valor))}%`;
}
</script>

<template>
    <main class="ranking">
        <section class="ranking__header">
            <div>
                <p class="ranking__eyebrow">
                    CORE DE COLABORACIÓN
                </p>

                <h1 class="ranking__title">
                    Ranking interfacultades
                </h1>

                <p class="ranking__description">
                    Comparación de estudiantes registrados
                    y asignados por facultad.
                </p>
            </div>

            <button
                type="button"
                class="ranking__period"
                disabled
            >
                Datos actuales
            </button>
        </section>

        <section class="ranking__summary">
            <article class="summary-card">
                <span class="summary-card__label">
                    Facultad líder
                </span>

                <strong class="summary-card__value">
                    {{ facultadLider }}
                </strong>
            </article>

            <article class="summary-card">
                <span class="summary-card__label">
                    Estudiantes registrados y asignados
                </span>

                <strong class="summary-card__value">
                    {{ numero(totalParticipantes) }}
                </strong>
            </article>

            <article class="summary-card">
                <span class="summary-card__label">
                    Facultades registradas
                </span>

                <strong class="summary-card__value">
                    {{ facultades.length }}
                </strong>
            </article>

            <article class="summary-card">
                <span class="summary-card__label">
                    Participación promedio
                </span>

                <strong class="summary-card__value">
                    {{ porcentaje(participacionPromedio) }} %
                </strong>
            </article>
        </section>

        <section class="ranking__content">
            <article class="ranking__panel">
                <h2 class="ranking__subtitle">
                    Tabla de posiciones
                </h2>

                <p class="ranking__scroll-hint">
                    Desliza para ver más →
                </p>

                <div class="ranking__table-wrapper">
                    <table class="ranking__table">
                        <thead>
                            <tr>
                                <th>Posición</th>
                                <th>Facultad</th>
                                <th>Registrados</th>
                                <th>Matriculados</th>
                                <th>Participación</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="facultad in facultades"
                                :key="facultad.id"
                            >
                                <td>
                                    #{{ facultad.posicion }}
                                </td>

                                <td>
                                    {{ facultad.nombre }}
                                </td>

                                <td>
                                    {{ numero(facultad.participantes) }}
                                </td>

                                <td>
                                    {{ numero(facultad.total_estudiantes) }}
                                </td>

                                <td>
                                    {{ porcentaje(facultad.participacion) }} %
                                </td>
                            </tr>

                            <tr v-if="facultades.length === 0">
                                <td colspan="5">
                                    Todavía no hay facultades registradas.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="ranking__panel">
                <h2 class="ranking__subtitle">
                    Participación por facultad
                </h2>

                <div class="ranking-chart">
                    <div
                        v-for="facultad in facultades"
                        :key="facultad.id"
                        class="ranking-chart__item"
                    >
                        <div class="ranking-chart__info">
                            <span>{{ facultad.nombre }}</span>

                            <strong>
                                {{ porcentaje(facultad.participacion) }} %
                            </strong>
                        </div>

                        <div class="ranking-chart__track">
                            <div
                                class="ranking-chart__bar"
                                :style="{
                                    width: anchoBarra(facultad.participacion),
                                }"
                            ></div>
                        </div>
                    </div>

                    <p
                        v-if="facultades.length === 0"
                        class="ranking__description"
                    >
                        No hay datos disponibles.
                    </p>
                </div>
            </article>
        </section>

        <p class="ranking__description" style="margin-top: 1rem">
            La participación se calcula dividiendo los estudiantes
            registrados y asignados entre el total de matriculados.
            El Índice de Colaboración y las ayudas verificadas
            se incorporarán cuando esos módulos estén implementados.
        </p>
    </main>
</template>

<style lang="scss">
@use '../../../scss/ranking';
</style>
