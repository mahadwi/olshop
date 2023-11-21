<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
    password: "",
});

const submit = () => {
    form.post(route("login"), {
        
    });
};

</script>

<template>
    <Head :title="lang().label.login"  />
    <div class="flex flex-col items-center mt-40 px-6 mx-auto md:h-screen pt:mt-0 dark:bg-gray-900">
        <!-- Card -->
        <div class="w-full max-w-xl p-6 space-y-8 sm:p-8 bg-white rounded-lg shadow dark:bg-gray-800">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Sign in
            </h2>
            <div v-if="status" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                {{ status }}
            </div>
            <form @submit.prevent="submit" method="POST" class="mt-8 space-y-6">
                <div>
                    <InputLabel for="email" :value="lang().label.email" />
                    <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :placeholder="lang().placeholder.email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div>
                    <InputLabel for="password" :value="lang().label.password" />
                    <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="username"
                    :placeholder="lang().placeholder.password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? lang().button.login + "..."
                            : lang().button.login
                    }}
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>