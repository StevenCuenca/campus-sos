import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'CampusSOS';

void createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),

    layout: (name) => {
        switch (true) {
            // Página pública inicial
            case name === 'Welcome':
                return null;

            // Todas las páginas de autenticación usan
            // su propio diseño CampusSOS
            case name.startsWith('auth/'):
                return null;

            // Configuración del usuario
            case name.startsWith('settings/'):
                return [AppLayout, SettingsLayout];

            // Resto de la aplicación
            default:
                return AppLayout;
        }
    },

    withApp: (app) => {
        app.directive('focus', {
            mounted: (el: HTMLElement, shouldFocus) => {
                if (shouldFocus.value !== false) {
                    el.focus();
                }
            },
        });
    },

    progress: {
        color: '#681f35',
    },
});

// Configura el tema al cargar la aplicación
initializeTheme();

// Escucha los mensajes flash del servidor
initializeFlashToast();
