<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { update } from '@/routes/password';

defineOptions({
    layout: null,
});

const props = defineProps<{
    token: string;
    email: string;
    passwordRules: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <Head title="Restablecer contraseña | CampusSOS" />

    <main
        class="flex min-h-screen items-center justify-center bg-[#f6f1ee] px-5 py-10"
    >
        <div
            class="grid w-full max-w-5xl overflow-hidden rounded-[28px] bg-white shadow-[0_20px_60px_rgba(74,24,39,0.12)] lg:grid-cols-2"
        >
            <!-- PANEL IZQUIERDO -->
            <section
                class="relative flex min-h-[660px] flex-col overflow-hidden bg-[#681f35] text-white"
            >
                <!-- FOTO CAMPUS -->
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
                            Recupera el acceso a tu comunidad.
                        </h1>

                        <p
                            class="mt-6 max-w-xs text-[17px] leading-7 text-white/80"
                        >
                            Crea una nueva contraseña segura y vuelve a
                            conectarte con CampusSOS.
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

                            <p class="text-xs font-bold">Comunidad</p>
                            <p class="mt-1 text-[10px] leading-4 text-white/65">
                                siempre contigo
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
                                protege tu cuenta
                            </p>
                        </div>

                        <div class="flex flex-col items-center">
                            <div
                                class="mb-3 flex h-10 w-10 items-center justify-center rounded-full border border-white/30"
                            >
                                <span class="text-lg">◇</span>
                            </div>

                            <p class="text-xs font-bold">CampusSOS</p>
                            <p class="mt-1 text-[10px] leading-4 text-white/65">
                                comunidad que ayuda
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RESTABLECER CONTRASEÑA -->
            <section
                class="flex min-h-[660px] items-center justify-center px-8 py-12 sm:px-14"
            >
                <div class="w-full max-w-sm">
                    <!-- ENCABEZADO -->
                    <div class="mb-8">
                        <p class="mb-2 text-sm font-semibold text-[#8d5263]">
                            RECUPERA TU CUENTA
                        </p>

                        <h2
                            class="text-3xl font-bold tracking-tight text-[#321820]"
                        >
                            Nueva contraseña
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-[#806d73]">
                            Ingresa y confirma tu nueva contraseña para volver a
                            acceder a CampusSOS.
                        </p>
                    </div>

                    <!-- FORMULARIO -->
                    <Form
                        v-bind="update.form()"
                        :transform="
                            (data) => ({
                                ...data,
                                token,
                                email: inputEmail,
                            })
                        "
                        :reset-on-success="[
                            'password',
                            'password_confirmation',
                        ]"
                        v-slot="{ errors, processing }"
                        class="space-y-5"
                    >
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
                                v-model="inputEmail"
                                type="email"
                                name="email"
                                autocomplete="email"
                                readonly
                                class="h-12 rounded-xl border-[#ddd1d4] bg-[#f8f5f4] px-4 text-[#806d73]"
                            />

                            <InputError :message="errors.email" class="mt-2" />
                        </div>

                        <!-- NUEVA CONTRASEÑA -->
                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-sm font-semibold text-[#4d373d]"
                            >
                                Nueva contraseña
                            </label>

                            <PasswordInput
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                autofocus
                                required
                                placeholder="Nueva contraseña"
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
                                name="password_confirmation"
                                autocomplete="new-password"
                                required
                                placeholder="Repite tu nueva contraseña"
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
                            :disabled="processing"
                            data-test="reset-password-button"
                            class="flex h-12 w-full items-center justify-center rounded-xl bg-[#681f35] text-sm font-semibold text-white transition hover:bg-[#54182b] disabled:opacity-60"
                        >
                            <Spinner v-if="processing" />
                            <span v-else>Restablecer contraseña</span>
                        </button>
                    </Form>

                    <div class="my-7 h-px bg-[#eee5e7]"></div>

                    <!-- VOLVER LOGIN -->
                    <div class="text-center">
                        <p class="text-sm text-[#806d73]">
                            ¿Recordaste tu contraseña?
                        </p>

                        <Link
                            :href="login()"
                            class="mt-1 inline-block text-sm font-bold text-[#681f35] underline underline-offset-4 transition-colors hover:text-[#8d2948]"
                        >
                            Volver a iniciar sesión
                        </Link>
                    </div>

                    <p class="mt-7 text-center text-xs text-[#b09da2]">
                        CampusSOS · Comunidad que ayuda
                    </p>
                </div>
            </section>
        </div>
    </main>
</template>
