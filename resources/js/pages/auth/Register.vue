<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    passwordRules: string;
}>();

defineOptions({
    layout: null,
});
</script>

<template>
    <Head title="Crear cuenta | CampusSOS" />

    <main
        class="flex min-h-screen items-center justify-center bg-[#f6f1ee] px-5 py-10"
    >
        <div
            class="grid w-full max-w-5xl overflow-hidden rounded-[28px] bg-white shadow-[0_20px_60px_rgba(74,24,39,0.12)] lg:grid-cols-2"
        >
            <!-- PANEL IZQUIERDO -->
            <section
                class="relative flex min-h-[700px] flex-col overflow-hidden bg-[#681f35] text-white"
            >
                <!-- FOTO DEL CAMPUS -->
                <div
                    class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('/images/campus-bg.jpg')"
                ></div>

                <!-- CAPA VINO -->
                <div
                    class="absolute inset-0 bg-gradient-to-b from-[#681f35]/95 via-[#681f35]/88 to-[#681f35]/95"
                ></div>

                <!-- DECORACIONES -->
                <div
                    class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#9a405b]/25"
                ></div>

                <div
                    class="absolute -top-20 -right-32 h-80 w-80 rounded-full bg-[#4c1427]/35"
                ></div>

                <div class="relative z-10 flex h-full flex-1 flex-col">
                    <!-- LOGO -->
                    <div class="flex justify-center px-10 pt-10">
                        <img
                            src="/images/campussos-logo.png"
                            alt="CampusSOS"
                            class="h-24 w-auto object-contain brightness-0 invert"
                        />
                    </div>

                    <!-- TEXTO -->
                    <div class="px-10 pt-9 lg:px-12">
                        <h1
                            class="max-w-sm text-4xl leading-[1.08] font-bold tracking-tight"
                        >
                            Únete a una comunidad que siempre está dispuesta a
                            ayudar.
                        </h1>

                        <p
                            class="mt-6 max-w-xs text-[17px] leading-7 text-white/80"
                        >
                            Crea tu cuenta, publica tus necesidades y conecta
                            con otros estudiantes dentro del campus.
                        </p>
                    </div>

                    <div class="flex-1"></div>

                    <!-- CURVA -->
                    <div
                        class="absolute -bottom-28 -left-28 h-64 w-[140%] rotate-[6deg] rounded-[50%] bg-[#8d2948]/65"
                    ></div>

                    <!-- BENEFICIOS -->
                    <div
                        class="relative z-10 grid grid-cols-3 gap-4 px-8 pt-16 pb-9 text-center"
                    >
                        <div class="flex flex-col items-center">
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-full border border-white/30"
                            >
                                <span class="text-lg">♡</span>
                            </div>

                            <p class="text-xs font-bold">Ayuda</p>
                            <p class="mt-1 text-[10px] leading-4 text-white/65">
                                a tu comunidad
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-full border border-white/30"
                            >
                                <span class="text-lg">✓</span>
                            </div>

                            <p class="text-xs font-bold">Acceso seguro</p>
                            <p class="mt-1 text-[10px] leading-4 text-white/65">
                                y confiable
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-full border border-white/30"
                            >
                                <span class="text-lg">◇</span>
                            </div>

                            <p class="text-xs font-bold">Conecta</p>
                            <p class="mt-1 text-[10px] leading-4 text-white/65">
                                con estudiantes
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- REGISTRO -->
            <section
                class="flex min-h-[700px] items-center justify-center px-8 py-10 sm:px-14"
            >
                <div class="w-full max-w-sm">
                    <!-- ENCABEZADO -->
                    <div class="mb-7">
                        <p class="mb-2 text-sm font-semibold text-[#8d5263]">
                            ÚNETE A CAMPUSSOS
                        </p>

                        <h2
                            class="text-3xl font-bold tracking-tight text-[#321820]"
                        >
                            Crea tu cuenta
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-[#806d73]">
                            Completa tus datos para formar parte de la
                            comunidad.
                        </p>
                    </div>

                    <!-- FORMULARIO -->
                    <Form
                        v-bind="store.form()"
                        :reset-on-success="[
                            'password',
                            'password_confirmation',
                        ]"
                        v-slot="{ errors, processing }"
                        class="space-y-4"
                    >
                        <!-- NOMBRE -->
                        <div>
                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold text-[#4d373d]"
                            >
                                Nombre completo
                            </label>

                            <Input
                                id="name"
                                type="text"
                                required
                                v-focus
                                :tabindex="1"
                                autocomplete="name"
                                name="name"
                                placeholder="Tu nombre completo"
                                class="h-12 rounded-xl border-[#ddd1d4] bg-white px-4"
                            />

                            <InputError :message="errors.name" class="mt-2" />
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label
                                for="email"
                                class="mb-2 block text-sm font-semibold text-[#4d373d]"
                            >
                                Correo electrónico
                            </label>

                            <Input
                                id="email"
                                type="email"
                                required
                                :tabindex="2"
                                autocomplete="email"
                                name="email"
                                placeholder="nombre@correo.com"
                                class="h-12 rounded-xl border-[#ddd1d4] bg-white px-4"
                            />

                            <InputError :message="errors.email" class="mt-2" />
                        </div>

                        <!-- CONTRASEÑA -->
                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-sm font-semibold text-[#4d373d]"
                            >
                                Contraseña
                            </label>

                            <PasswordInput
                                id="password"
                                required
                                :tabindex="3"
                                autocomplete="new-password"
                                name="password"
                                placeholder="Crea una contraseña"
                                :passwordrules="passwordRules"
                                class="h-12 rounded-xl border-[#ddd1d4]"
                            />

                            <InputError
                                :message="errors.password"
                                class="mt-2"
                            />
                        </div>

                        <!-- CONFIRMAR CONTRASEÑA -->
                        <div>
                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-semibold text-[#4d373d]"
                            >
                                Confirmar contraseña
                            </label>

                            <PasswordInput
                                id="password_confirmation"
                                required
                                :tabindex="4"
                                autocomplete="new-password"
                                name="password_confirmation"
                                placeholder="Repite tu contraseña"
                                :passwordrules="passwordRules"
                                class="h-12 rounded-xl border-[#ddd1d4]"
                            />

                            <InputError
                                :message="errors.password_confirmation"
                                class="mt-2"
                            />
                        </div>

                        <!-- BOTÓN -->
                        <button
                            type="submit"
                            :tabindex="5"
                            :disabled="processing"
                            data-test="register-user-button"
                            class="mt-2 flex h-12 w-full items-center justify-center rounded-xl bg-[#681f35] text-sm font-semibold text-white transition hover:bg-[#54182b] disabled:opacity-60"
                        >
                            <Spinner v-if="processing" />
                            <span v-else>Crear cuenta</span>
                        </button>
                    </Form>

                    <!-- VOLVER AL LOGIN -->
                    <div class="my-6 h-px bg-[#eee5e7]"></div>

                    <div class="text-center">
                        <p class="text-sm text-[#806d73]">
                            ¿Ya tienes una cuenta?
                        </p>

                        <Link
                            :href="login()"
                            :tabindex="6"
                            class="mt-1 inline-block text-sm font-bold text-[#681f35] underline underline-offset-4 transition-colors hover:text-[#8d2948]"
                        >
                            Iniciar sesión
                        </Link>
                    </div>

                    <p class="mt-6 text-center text-xs text-[#b09da2]">
                        CampusSOS · Comunidad que ayuda
                    </p>
                </div>
            </section>
        </div>
    </main>
</template>
