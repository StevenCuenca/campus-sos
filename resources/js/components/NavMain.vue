<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import {
    SidebarGroup,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup class="px-2 py-1">
        <SidebarMenu class="gap-1">

            <SidebarMenuItem
                v-for="item in items"
                :key="item.title"
            >
                <SidebarMenuButton
                    as-child
                    :is-active="isCurrentUrl(item.href)"
                    :tooltip="item.title"
                    class="
                        h-10 rounded-xl px-3
                        text-sidebar-foreground/80
                        transition-all duration-200

                        hover:bg-white/10
                        hover:text-white

                        data-[active=true]:bg-white
                        data-[active=true]:font-semibold
                        data-[active=true]:text-[#7A1631]
                        data-[active=true]:shadow-sm
                    "
                >
                    <Link :href="item.href">
                        <component
                            :is="item.icon"
                            class="size-4"
                        />

                        <span>
                            {{ item.title }}
                        </span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>

        </SidebarMenu>
    </SidebarGroup>
</template>