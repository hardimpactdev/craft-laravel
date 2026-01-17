<script setup lang="ts">
import {
    Button,
    Input,
    InputError,
} from "@hardimpactdev/craft-vue";
import { LoaderCircle } from "lucide-vue-next";
import { computed, ref } from "vue";
import { OTPInput } from "vue-input-otp";

interface AuthConfigContent {
    title: string;
    description: string;
    toggleText: string;
}

const showRecoveryInput = ref<boolean>(false);
const code = ref<string>("");

const authConfigContent = computed<AuthConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: "Recovery Code",
            description:
                "Please confirm access to your account by entering one of your emergency recovery codes.",
            toggleText: "login using an authentication code",
        };
    }

    return {
        title: "Authentication Code",
        description:
            "Enter the authentication code provided by your authenticator application.",
        toggleText: "login using a recovery code",
    };
});

const form = useForm({
    code: "",
    recovery_code: "",
});

const submit = () => {
    if (showRecoveryInput.value) {
        form.recovery_code = form.recovery_code;
        form.code = "";
    } else {
        form.code = code.value;
        form.recovery_code = "";
    }

    form.submit("/two-factor-challenge", {
        onError: () => {
            code.value = "";
        },
    });
};

const toggleRecoveryMode = () => {
    showRecoveryInput.value = !showRecoveryInput.value;
    form.reset();
    code.value = "";
};
</script>

<template>
    <AuthLayout
        :title="authConfigContent.title"
        :description="authConfigContent.description"
    >
        <Head title="Two-Factor Authentication" />

        <div class="space-y-6">
            <template v-if="!showRecoveryInput">
                <form @submit.prevent="submit" class="space-y-4">
                    <div
                        class="flex flex-col items-center justify-center space-y-3 text-center"
                    >
                        <div class="flex w-full items-center justify-center">
                            <OTPInput
                                v-model="code"
                                :num-inputs="6"
                                input-type="number"
                                :should-auto-focus="true"
                                :disabled="form.processing"
                                input-classes="w-10 h-12 text-center text-lg border border-input rounded-md mx-1 focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                        </div>
                        <InputError :message="form.errors.code" />
                    </div>
                    <Button
                        type="submit"
                        class="w-full"
                        :disabled="form.processing"
                    >
                        <LoaderCircle
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin"
                        />
                        Continue
                    </Button>
                    <div class="text-center text-sm text-muted-foreground">
                        <span>or you can </span>
                        <button
                            type="button"
                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current dark:decoration-neutral-500"
                            @click="toggleRecoveryMode"
                        >
                            {{ authConfigContent.toggleText }}
                        </button>
                    </div>
                </form>
            </template>

            <template v-else>
                <form @submit.prevent="submit" class="space-y-4">
                    <Input
                        v-model="form.recovery_code"
                        type="text"
                        placeholder="Enter recovery code"
                        autofocus
                        required
                    />
                    <InputError :message="form.errors.recovery_code" />
                    <Button
                        type="submit"
                        class="w-full"
                        :disabled="form.processing"
                    >
                        <LoaderCircle
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin"
                        />
                        Continue
                    </Button>

                    <div class="text-center text-sm text-muted-foreground">
                        <span>or you can </span>
                        <button
                            type="button"
                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current dark:decoration-neutral-500"
                            @click="toggleRecoveryMode"
                        >
                            {{ authConfigContent.toggleText }}
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </AuthLayout>
</template>
