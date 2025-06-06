<script setup lang="ts">
import { AuthSimpleLayout as AuthLayout } from "@livtoff/ui";

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout
        :title="title"
        :description="description"
        :logoLink="Controllers.HomeController.show()"
    >
        <template #logo>
            <AppLogoIcon
                class="size-9 fill-current text-[var(--foreground)] dark:text-white"
            />
        </template>
        <slot />
    </AuthLayout>
</template>
