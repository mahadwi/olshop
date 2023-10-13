<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    roles: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    email: "",
    no_hp: "",
    password: "",
    password_confirmation: "",
    role: "admin",
});

const create = () => {
    form.post(route("users.store"), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

watchEffect(() => {
    if (props.show) {
        form.errors = {};
    }
});

const roles = props.roles?.map((role) => ({
    label: role.name,
    value: role.name,
}));
</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <form class="p-6" @submit.prevent="create">
                <h2
                    class="text-lg font-medium text-slate-900 dark:text-slate-100"
                >
                    Create {{ props.title }}
                </h2>
                <div class="my-6 space-y-4">
                    <div>
                        <InputLabel for="name" value="Name" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            placeholder="Name"
                            :error="form.errors.name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            placeholder="Email"
                            :error="form.errors.email"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <InputLabel for="no_hp" value="No HP" />
                        <TextInput
                            id="no_hp"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.no_hp"
                            placeholder="No HP"
                            :error="form.errors.no_hp"
                        />
                        <InputError class="mt-2" :message="form.errors.no_hp" />
                    </div>
                    <div>
                        <InputLabel
                            for="password"
                            value="Password"
                        />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            placeholder="Password"
                            :error="form.errors.password"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>
                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Konfirmasi Password"
                        />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            placeholder="Konfirmasi Password"
                            :error="form.errors.password_confirmation"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>
                    <div>
                        <InputLabel for="role" value="Role" />
                        <SelectInput
                            id="role"
                            class="mt-1 block w-full"
                            v-model="form.role"
                            required
                            :dataSet="roles"
                        >
                        </SelectInput>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <SecondaryButton
                        :disabled="form.processing"
                        @click="emit('close')"
                    >
                        Close
                    </SecondaryButton>
                    <PrimaryButton
                        type="submit"
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? "Save" + "..."
                                : "Save"
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
