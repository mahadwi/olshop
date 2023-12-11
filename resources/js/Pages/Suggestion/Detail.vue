<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue'

import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    suggestion: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    email: "",
    suggestion: "",
});

watchEffect(() => {
    if (props.show) {
        form.name = props.suggestion?.name;
        form.email = props.suggestion?.email;
        form.suggestion = props.suggestion?.suggestion;
        form.errors = {};
    }
});


</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <form class="p-6">
                <h2
                    class="text-lg font-medium text-slate-900 dark:text-slate-100"
                >
                    {{ lang().label.detail }} {{ props.title }}
                </h2>
                <div class="my-6 space-y-4">
                    
                    <fwb-input v-model="form.name" :placeholder="lang().placeholder.name" :label="lang().placeholder.name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                    
                </div>
                <div class="my-6 space-y-4">
                    
                    <fwb-input v-model="form.email" :placeholder="lang().label.email" :label="lang().label.email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                    
                </div>
                <div class="my-6 space-y-4">
                    <FwbTextarea rows="4" :placeholder="lang().label.suggestion" v-model="form.suggestion" :label="lang().label.suggestion" />
                    <InputError class="mt-2" :message="form.errors.suggestion" />
                </div>
               
                <div class="flex justify-end">
                    <SecondaryButton
                        :disabled="form.processing"
                        @click="emit('close')"
                    >
                        {{ lang().button.close }}
                    </SecondaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
