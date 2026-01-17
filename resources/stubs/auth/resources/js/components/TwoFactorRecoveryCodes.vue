<script setup lang="ts">
import { Button, Card, CardContent, CardDescription, CardHeader, CardTitle } from "@hardimpactdev/craft-vue";
import { Eye, EyeOff, LockKeyhole, RefreshCw, LoaderCircle } from "lucide-vue-next";
import { nextTick, onMounted, ref, useTemplateRef } from "vue";
import { useTwoFactorAuth } from "@/composables/useTwoFactorAuth";

const { recoveryCodesList, fetchRecoveryCodes, errors } = useTwoFactorAuth();
const isRecoveryCodesVisible = ref<boolean>(false);
const recoveryCodeSectionRef = useTemplateRef("recoveryCodeSectionRef");
const isRegenerating = ref(false);

const toggleRecoveryCodesVisibility = async () => {
    if (!isRecoveryCodesVisible.value && !recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }

    isRecoveryCodesVisible.value = !isRecoveryCodesVisible.value;

    if (isRecoveryCodesVisible.value) {
        await nextTick();
        recoveryCodeSectionRef.value?.scrollIntoView({ behavior: "smooth" });
    }
};

const regenerateCodes = async () => {
    isRegenerating.value = true;
    try {
        const response = await fetch("/user/two-factor-recovery-codes", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
        });
        if (response.ok) {
            await fetchRecoveryCodes();
        }
    } finally {
        isRegenerating.value = false;
    }
};

onMounted(async () => {
    if (!recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }
});
</script>

<template>
    <Card class="w-full">
        <CardHeader>
            <CardTitle class="flex gap-3">
                <LockKeyhole class="size-4" />2FA Recovery Codes
            </CardTitle>
            <CardDescription>
                Recovery codes let you regain access if you lose your 2FA
                device. Store them in a secure password manager.
            </CardDescription>
        </CardHeader>
        <CardContent>
            <div
                class="flex flex-col gap-3 select-none sm:flex-row sm:items-center sm:justify-between"
            >
                <Button @click="toggleRecoveryCodesVisibility" class="w-fit">
                    <component
                        :is="isRecoveryCodesVisible ? EyeOff : Eye"
                        class="size-4"
                    />
                    {{ isRecoveryCodesVisible ? "Hide" : "View" }} Recovery
                    Codes
                </Button>

                <Button
                    v-if="isRecoveryCodesVisible && recoveryCodesList.length"
                    variant="secondary"
                    @click="regenerateCodes"
                    :disabled="isRegenerating"
                >
                    <LoaderCircle
                        v-if="isRegenerating"
                        class="size-4 animate-spin"
                    />
                    <RefreshCw v-else class="size-4" />
                    Regenerate Codes
                </Button>
            </div>
            <div
                :class="[
                    'relative overflow-hidden transition-all duration-300',
                    isRecoveryCodesVisible
                        ? 'h-auto opacity-100'
                        : 'h-0 opacity-0',
                ]"
            >
                <div
                    v-if="errors?.length"
                    class="mt-6 rounded-lg border border-destructive bg-destructive/10 p-4"
                >
                    <p
                        v-for="(error, index) in errors"
                        :key="index"
                        class="text-sm text-destructive"
                    >
                        {{ error }}
                    </p>
                </div>
                <div v-else class="mt-3 space-y-3">
                    <div
                        ref="recoveryCodeSectionRef"
                        class="grid gap-1 rounded-lg bg-muted p-4 font-mono text-sm"
                    >
                        <div v-if="!recoveryCodesList.length" class="space-y-2">
                            <div
                                v-for="n in 8"
                                :key="n"
                                class="h-4 animate-pulse rounded bg-muted-foreground/20"
                            ></div>
                        </div>
                        <div
                            v-else
                            v-for="(code, index) in recoveryCodesList"
                            :key="index"
                        >
                            {{ code }}
                        </div>
                    </div>
                    <p class="text-xs text-muted-foreground select-none">
                        Each recovery code can be used once to access your
                        account and will be removed after use. If you need more,
                        click
                        <span class="font-bold">Regenerate Codes</span> above.
                    </p>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
