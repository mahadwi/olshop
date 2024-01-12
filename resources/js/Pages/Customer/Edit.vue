<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue'

const props = defineProps({
    show: Boolean,
    title: String,
    customer: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    email: "",
    phone: "",
    alamat: "",
});

const update = () => {
    form.put(route("customer.update", props.customer?.id), {
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
        form.name = props.customer?.name;
        form.email = props.customer?.email;
        form.phone = props.customer?.phone;
        form.alamat = props.customer?.alamat;
        form.errors = {};
    }
});


</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <form class="p-6" @submit.prevent="update">
                <h2
                    class="text-lg font-medium text-slate-900 dark:text-slate-100"
                >
                    {{ lang().label.edit }} {{ props.title }}
                </h2>
                <div class="my-6 space-y-4">
                    <div>
                        <InputLabel for="name" :value="lang().label.name" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"                            
                            :placeholder="lang().placeholder.name"
                            :error="form.errors.name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="email" :value="lang().label.email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full cursor-not-allowed"
                            disabled
                            v-model="form.email"
                            :placeholder="lang().placeholder.email"
                            :error="form.errors.email"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <InputLabel for="no_hp" :value="lang().label.no_hp" />
                        <TextInput
                            id="no_hp"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            :error="form.errors.phone"
                        />
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>
                    <div>
                        <FwbTextarea rows="4" :placeholder="lang().label.address" v-model="form.alamat" :label="lang().label.address" />
                        <InputError class="mt-2" :message="form.errors.alamat" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <SecondaryButton
                        :disabled="form.processing"
                        @click="emit('close')"
                    >
                        {{ lang().button.close }}
                    </SecondaryButton>
                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="update"
                    >
                        {{
                            form.processing
                                ? lang().button.save + "..."
                                : lang().button.save
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
