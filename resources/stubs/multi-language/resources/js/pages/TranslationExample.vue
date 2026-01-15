<script setup lang="ts">
import { useLanguage } from "@hardimpactdev/craft-vue";

const { updateLanguage } = useLanguage();
</script>

<template>
    <Head title="Home" />
    <Link :href="Controllers.HomeController.show()">Home</Link>

    {{ __("auth.failed") }}

    <button @click="updateLanguage('nl')">Update Language</button>
</template>
