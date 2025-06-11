<script setup lang="ts">
import { useLanguage } from '@livtoff/ui';

const { language, updateLanguage } = useLanguage();

console.log(language.value);

</script>

<template>
    <Head title="Home" />
    <Link :href="Controllers.HomeController.show()">Home</Link>

    {{ trans("auth.failed") }}

    <button @click="updateLanguage('nl')">Update Language</button>
</template>
