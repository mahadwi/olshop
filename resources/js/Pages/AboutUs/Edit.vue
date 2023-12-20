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
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    show: Boolean,
    title: String,
    about: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    title: "",
    title_en: "",
    description: "",
    description_en: "",
    address: "",
    detail_address: "",
    maps: "",
    image: "",
});

const update = () => {
    form.post(route("about.update", props.about?.id), {
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
        form.title = props.about?.title;
        form.title_en = props.about?.title_en;
        form.description = props.about?.description;
        form.description_en = props.about?.description_en;
        form.address = props.about?.address;
        form.detail_address = props.about?.detail_address;
        form.maps = props.about?.maps;
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
                        <fwb-input v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div>
                        <fwb-input v-model="form.title_en" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                        <InputError class="mt-2" :message="form.errors.title_en" />
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.description_en" />
                        <InputError class="mt-2" :message="form.errors.description_en" />
                    </div>
                    <div>
                        <FwbTextarea rows="4" :placeholder="lang().label.address" v-model="form.address" :label="lang().label.address" />
                        <InputError class="mt-2" :message="form.errors.address" />
                    </div>
                    <div>
                        <FwbTextarea rows="2" :placeholder="lang().label.detail_address" v-model="form.detail_address" :label="lang().label.detail_address" />
                        <InputError class="mt-2" :message="form.errors.detail_address" />
                    </div>

                    <div>
                        <FwbTextarea rows="2" :placeholder="lang().label.maps" v-model="form.maps" :label="lang().label.maps" />
                        <InputError class="mt-2" :message="form.errors.maps" />
                    </div>
                    <div>
                        <fwb-file-input accept="image/*" v-model="form.image" :label="lang().label.image" />

                        <InputError class="mt-2" :message="form.errors.image" />
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
