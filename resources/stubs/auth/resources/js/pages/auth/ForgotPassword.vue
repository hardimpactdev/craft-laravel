<script setup lang="ts">
import {
    Button,
    Input,
    Label,
    InputError,
    TextLink,
} from "@hardimpactdev/craft-vue";
import { LoaderCircle } from "lucide-vue-next";

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: "",
});

const submit = () => {
    form.submit(Controllers.Auth.ForgotPasswordController.sendResetLink(), {
        onFinish: () => form.reset("email"),
    });
};
</script>

<template>
    <AuthLayout
        title="Forgot password"
        description="Enter your email to receive a password reset link"
    >
        <Head title="Forgot password" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <div class="space-y-6">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="off"
                        v-model="form.email"
                        autofocus
                        placeholder="email@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <Button class="w-full" :disabled="form.processing">
                        <LoaderCircle
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin"
                        />
                        Email password reset link
                    </Button>
                </div>
            </form>

            <div class="space-x-1 text-center text-sm text-muted-foreground">
                <span>Or, return to</span>
                <TextLink :href="Controllers.Auth.LoginController.login()">
                    log in
                </TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
