<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbSelect, FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue'
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';


const props = defineProps({
    show: Boolean,
    title: String,
    section:Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    section: "",
    section_en: "",
    title: "",
    description: "",
    title_en: "",
    description_en: "",
});

const create = () => {
    form.post(route("faq.store"), {
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

const section_en = props.section.map((data) => ({
    name: data.section_en,
    value: data.id
}));

const section = props.section.map((data) => ({
    name: data.section,
    value: data.id
}));

const changeSection = () => {
    const selectedValue = form.section;
    form.section_en = selectedValue;
};

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
                    <FwbSelect
                        v-model="form.section"
                        :options="section"
                        :label="lang().label.section"
                        @change="changeSection"
                    />
                    <InputError class="mt-2" :message="form.errors.section" />
                </div>
                <div class="my-6 space-y-4">
                    <FwbSelect
                        v-model="form.section_en"
                        :options="section_en"
                        :label="lang().label.section_en"
                        disabled
                    />
                    <InputError class="mt-2" :message="form.errors.section_en" />
                </div>
                <div class="my-6 space-y-4">
                    <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                    <QuillEditor theme="snow" toolbar="essential" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div class="my-6 space-y-4">
                    <FwbInput v-model="form.title_en" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                    <InputError class="mt-2" :message="form.errors.title_en" />
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                    <QuillEditor theme="snow" toolbar="essential" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.description_en" />
                    <InputError class="mt-2" :message="form.errors.description_en" />
                </div>
                <div class="flex justify-end mt-5">
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
