<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbSelect, FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue'
import axios from 'axios';

import { useForm, usePage, router } from "@inertiajs/vue3";
import { watchEffect, ref } from "vue";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    show: Boolean,
    title: String,
    faq: Object,
    section:Object
});

const emit = defineEmits(["close"]);

const form = useForm({
    section: "",
    title: "",
    description: "",
});

const update = () => {
    form.put(route("faq.update", props.faq?.id), {
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
        form.section = props.faq?.section;
        form.title = props.faq?.title;
        form.description = props.faq?.description;
        form.errors = {};
    }
});

const section = Object.values(props.section).map((data) => ({
    name: data,
    value: data
}))

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
                    
                    <FwbSelect
                        v-model="form.section"
                        :options="section"
                        :label="lang().label.section"
                    />

                    <InputError class="mt-2" :message="form.errors.section" />
                    
                </div>
                <div class="my-6 space-y-4">
                    
                    <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                    <InputError class="mt-2" :message="form.errors.title" />
                    
                </div>
                <div class="">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                    <QuillEditor theme="snow" toolbar="essential" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div class="flex justify-end mt-5">
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
