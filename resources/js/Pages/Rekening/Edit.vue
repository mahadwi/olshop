<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue';

import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import Multiselect from "@vueform/multiselect";


const props = defineProps({
    show: Boolean,
    title: String,
    rekening: Object,
    bankCode: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    bank: "",
    bank_account_holder: "",
    bank_account_number: "",
    logo: "",
});

const update = () => {
    form.post(route("rekening.update", props.rekening?.id), {
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
        form.bank = props.rekening?.bank;
        form.bank_account_holder = props.rekening?.bank_account_holder;
        form.bank_account_number = props.rekening?.bank_account_number;
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
                        <InputLabel for="bank" :value="lang().label.bank" />

                        <Multiselect
                        id="bank"
                        v-model="form.bank"
                        :options="props.bankCode"
                        track-by="label"
                        label="label"
                        :searchable="true"
                        placeholder="Select"
                        />

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
                        <FwbFileInput accept="image/*" v-model="form.logo" :label="lang().label.logo" />
    
                        <InputError class="mt-2" :message="form.errors.logo" />
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
