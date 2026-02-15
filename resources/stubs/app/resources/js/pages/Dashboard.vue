<script setup lang="ts">
import { AppSidebarLayout, useAppNavigation } from "@hardimpactdev/craft-ui";
import { type BreadcrumbItem } from "@/types";

const appNav = useAppNavigation();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: "/dashboard",
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppSidebarLayout :breadcrumbs="breadcrumbs" v-bind="appNav">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-muted/50"
                >
                    <!-- Placeholder content -->
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-muted/50"
                >
                    <!-- Placeholder content -->
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-muted/50"
                >
                    <!-- Placeholder content -->
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min bg-muted/50"
            >
                <!-- Placeholder content -->
            </div>
        </div>
    </AppSidebarLayout>
</template>
