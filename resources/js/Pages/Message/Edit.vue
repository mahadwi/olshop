<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea , FwbFileInput } from 'flowbite-vue';

import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { ref } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    show: Boolean,
    title: String,
    message: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    firstName: "",
    lastName: "",
    email: "",
    handphone: "",
    subject: "",
    message: "",
    reply_message: "",
});

const update = () => {
    form.put(route("message.update", props.message?.id), {
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
        form.firstName = props.message?.firstName;
        form.lastName = props.message?.lastName;
        form.email = props.message?.email;
        form.handphone = props.message?.handphone;
        form.email = props.message?.email;
        form.subject = props.message?.subject;
        form.message = props.message?.message;
        form.reply_message = props.message?.reply_message;
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
                    <fwb-input v-model="form.firstName" :placeholder="lang().label.first_name" :label="lang().label.first_name" disabled />
                    <InputError class="mt-2" :message="form.errors.firstName" />
                </div>
                <div class="my-6 space-y-4">
                    <fwb-input v-model="form.lastName" :placeholder="lang().label.last_name" :label="lang().label.last_name" disabled />
                    <InputError class="mt-2" :message="form.errors.lastName" />
                </div>
                <div class="my-6 space-y-4">
                    <fwb-input v-model="form.email" :placeholder="lang().label.email" :label="lang().label.email" disabled />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="my-6 space-y-4">
                    <fwb-input v-model="form.handphone" :placeholder="lang().label.no_hp" :label="lang().label.no_hp" disabled />
                    <InputError class="mt-2" :message="form.errors.handphone" />
                </div>
                <div class="my-6 space-y-4">
                    <fwb-input v-model="form.subject" :placeholder="lang().label.subject" :label="lang().label.subject" disabled />
                    <InputError class="mt-2" :message="form.errors.subject" />
                </div>
                <div class="my-6 space-y-4">
                    <fwb-textarea
                        v-model="form.message"
                        :label="lang().label.message"
                        readonly
                        rows="4"
                    />
                    <InputError class="mt-2" :message="form.errors.message" />
                </div>
                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.reply_message}} </label>
                    <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.reply_message" v-model:content="form.reply_message" />
                    <InputError class="mt-2" :message="form.errors.reply_message" />
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
                                ? lang().button.save_send + "..."
                                : lang().button.save_send
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
