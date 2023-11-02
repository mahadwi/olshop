<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Select, Input, Textarea, FileInput } from 'flowbite-vue'
import vueFilePond, { setOptions } from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import axios from 'axios';

import 'filepond/dist/filepond.min.css';
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"

import { useForm, usePage, router } from "@inertiajs/vue3";
import { watchEffect, ref } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    banner: Object,
    bannerSection:Object
});

const emit = defineEmits(["close"]);

const form = useForm({
    section: "",
    title: "",
    description: "",
});

const update = () => {
    form.put(route("banner.update", props.banner?.id), {
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
        form.section = props.banner?.section;
        form.title = props.banner?.title;
        form.description = props.banner?.description;
        form.errors = {};
    }
});

const bannerSection = Object.values(props.bannerSection).map((data) => ({
    name: data,
    value: data
}))

//filepond
const filepondRef = ref();
const images = ref([]);
const page = usePage();

const filepond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
);

// Set global options on filepond init
const handleFilePondInit = () => {
    setOptions({
        server: {
            load: (source, load, error, progress, abort, headers) => {
                // axios.get(route('product.get-image', source)).then(res).then(load);
                let request = new XMLHttpRequest();
                request.open('GET', route('image.get-image', source));
                request.responseType = "blob";
                request.onreadystatechange = () => request.readyState === 4 && load(request.response);
                request.send();
            },
            process:(fieldName, file, metadata, load, error, progress, abort) => {
                const formData = new FormData();
                formData.append(fieldName, file, file.name);

                const request = new XMLHttpRequest();
                request.open('POST', route('banner.upload-image', props.banner?.id));
                request.setRequestHeader('X-CSRF-TOKEN' , page.props.token);

                request.upload.onprogress = (e) => {
                    progress(e.lengthComputable, e.loaded, e.total);
                };

                request.onload = function() {
                    if (request.status >= 200 && request.status < 300) {
                        props.banner.image.push(request.responseText)
                        load(request.responseText);
                    }
                    else {
                        error('Error');
                    }
                };

                request.send(formData);
                return {
                        abort: () => {
                            request.abort();
                            abort();
                        }
                };
            },
            revert:(src, load) => {
                axios.post(route('image.delete-image'), {name: src});


                load();
            },
            remove:(src, load) => {
                axios.post(route('image.delete-image'), {name: src});

                load();
            },
            headers: { 'X-CSRF-TOKEN': page.props.token }
        },
        files: props.banner.image
    });
};
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
                    
                    <Select
                        v-model="form.section"
                        :options="bannerSection"
                        :label="lang().label.section"
                    />

                    <InputError class="mt-2" :message="form.errors.section" />
                    
                </div>
                <div class="my-6 space-y-4">
                    
                    <Input v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                    <InputError class="mt-2" :message="form.errors.title" />
                    
                </div>
                <div class="my-6 space-y-4">
                    <Textarea rows="4" :placeholder="lang().label.description" v-model="form.description" :label="lang().label.description" />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div class="my-6 space-y-4">
                    <filepond
                        name="image"
                        ref="filepondRef"
                        label-idle="Upload Images..."
                        :allow-multiple="true"
                        accepted-file-types="image/*"
                        :files="images"
                        @init="handleFilePondInit"
                    />

                    <InputError class="mt-2" :message="form.errors.image" />
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
