<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue'
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    email: "",
    phone: "",
    address: "",
    ktp: "",
    bank: "",
    bank_account_holder: "",
    bank_account_number: "",
});

const create = () => {
    form.post(route("vendor.store"), {
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

</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <form class="p-6" @submit.prevent="create">
                <h2
                    class="text-lg font-medium text-slate-900 dark:text-slate-100"
                >
                    {{ lang().label.add }} {{ props.title }}
                </h2>
                <div class="my-6 space-y-4">
                    <div>
                        <FwbInput v-model="form.name" :placeholder="lang().placeholder.name" :label="lang().placeholder.name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <FwbInput v-model="form.email" :placeholder="lang().label.email" :label="lang().label.email" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <FwbInput v-model="form.phone" :placeholder="lang().label.no_hp" :label="lang().label.no_hp" />
                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>
                  
                    <div>
                        <FwbInput v-model="form.ktp" :placeholder="lang().label.ktp" :label="lang().label.ktp" />
                        <InputError class="mt-2" :message="form.errors.ktp" />
                    </div>
                    <div>
                        <FwbInput v-model="form.bank" :placeholder="lang().label.bank" :label="lang().label.bank" />
                        <InputError class="mt-2" :message="form.errors.bank" />
                    </div>
                    <div>
                        <FwbInput v-model="form.bank_account_number" :placeholder="lang().label.bank_account_number" :label="lang().label.bank_account_number" />
                        <InputError class="mt-2" :message="form.errors.bank_account_number" />
                    </div>
                    <div>
                        <FwbInput v-model="form.bank_account_holder" :placeholder="lang().label.bank_account_holder" :label="lang().label.bank_account_holder" />
                        <InputError class="mt-2" :message="form.errors.bank_account_holder" />
                    </div>
                    <div>
                        <FwbTextarea rows="4" :placeholder="lang().label.address" v-model="form.address" :label="lang().label.address" />
                        <InputError class="mt-2" :message="form.errors.address" />
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
                        type="submit"
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? lang().button.add + "..."
                                : lang().button.add
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
