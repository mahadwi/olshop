<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue';
import Multiselect from "@vueform/multiselect";

import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { usePage } from '@inertiajs/vue3';


const props = defineProps({
    show: Boolean,
    title: String,
    agreement: Object,
});

const bahasa = usePage().props.language.original;

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    file: "",
    is_active: "",
});

const dataStatus = [
    {
        label: bahasa.label.active,
        value: true
    },
    {
        label: bahasa.label.not_active,
        value: false
    }    
];

const update = () => {
    form.post(route("agreement.update", props.agreement?.id), {
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
        form.name = props.agreement?.name;
        form.is_active = props.agreement?.is_active;
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

                    <fwb-input v-model="form.name" :placeholder="lang().placeholder.name" :label="lang().placeholder.name" />
                    <InputError class="mt-2" :message="form.errors.name" />

                </div>                
                <div class="my-6 space-y-4">
                    <FwbFileInput accept="image/*" v-model="form.file" :label="lang().label.file" />

                    <InputError class="mt-2" :message="form.errors.file" />
                </div>
                <div class="my-6 space-y-4">
                    <InputLabel for="status" value="Status" />
                    <SelectInput
                        id="status"
                        class="mt-1 block w-full"
                        v-model="form.is_active"
                        required
                        :dataSet="dataStatus"
                    >
                    </SelectInput>
                    <InputError class="mt-2" :message="form.errors.is_active" />
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

