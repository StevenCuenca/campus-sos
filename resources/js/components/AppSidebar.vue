<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Home, PlusCircle, ClipboardList, Settings } from '@lucide/vue';

import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Inicio',
        href: dashboard(),
        icon: Home,
    },
    {
        title: 'Publicar SOS',
        href: '/solicitudes/create',
        icon: PlusCircle,
    },
    {
        title: 'Mis SOS',
        href: '/solicitudes',
        icon: ClipboardList,
    },
];

const settingsNavItems: NavItem[] = [
    {
        title: 'Configuración',
        href: '/settings/profile',
        icon: Settings,
    },
];
</script>

<template>
    <Sidebar
        collapsible="icon"
        variant="inset"
        class="border-r border-[#64152A]/20"
    >
        <!-- LOGO CAMPUSSOS -->
        <SidebarHeader class="pt-4">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="h-auto py-3">
                        <Link
                            :href="dashboard()"
                            class="flex items-center gap-3"
                        >
                            <!-- ICONO -->
                            <div
                                class="flex size-11 shrink-0 items-center justify-center overflow-hidden rounded-xl shadow-sm"
                            >
                                <img
                                    src="/images/campussos-icon.png"
                                    alt="CampusSOS"
                                    class="h-full w-full object-cover"
                                />
                            </div>

                            <!-- MARCA -->
                            <div
                                class="grid min-w-0 flex-1 text-left leading-tight group-data-[collapsible=icon]:hidden"
                            >
                                <span
                                    class="truncate text-lg font-bold text-[#7A1631] dark:text-[#F4DCE3]"
                                >
                                    CampusSOS
                                </span>

                                <span
                                    class="truncate text-xs text-muted-foreground"
                                >
                                    Comunidad que ayuda
                                </span>
                            </div>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- NAVEGACIÓN PRINCIPAL -->
        <SidebarContent class="px-2">
            <div
                class="mt-4 mb-2 px-2 text-[11px] font-semibold tracking-widest text-muted-foreground uppercase group-data-[collapsible=icon]:hidden"
            >
                Principal
            </div>

            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- FOOTER -->
        <SidebarFooter class="px-2 pb-4">
            <div
                class="mb-1 px-2 text-[11px] font-semibold tracking-widest text-muted-foreground uppercase group-data-[collapsible=icon]:hidden"
            >
                Mi cuenta
            </div>

            <NavMain :items="settingsNavItems" />

            <div class="my-2 h-px bg-[#7A1631]/10 dark:bg-white/10"></div>

            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>
