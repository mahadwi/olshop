<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbSelect, FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue'
import vueFilePond, { setOptions } from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import axios from 'axios';

import 'filepond/dist/filepond.min.css';
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"

import { useForm, usePage, router } from "@inertiajs/vue3";
import { watchEffect, ref } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    gallery: Object,
    product: Object,
    gallerySection:Object
});

const emit = defineEmits(["close"]);

const form = useForm({
    section: "",
    title: "",
    product_id:''
});

const update = () => {
    form.put(route("gallery.update", props.gallery?.id), {
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
        form.section = props.gallery?.section;
        form.title = props.gallery?.title;
        form.product_id = props.gallery?.product_id;
        form.errors = {};
    }
});

const gallerySection = Object.values(props.gallerySection).map((data) => ({
    name: data,
    value: data,
}));

let product = props.product?.map((data) => ({
    label: data.name,
    value: data.id,
}));


product = [{label : 'Select...', value: ''}, ...product];

//filepond
const filepondRef = ref();
const images = ref([]);
const page = usePage();

const filepond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFileValidateSize
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
                request.open('POST', route('gallery.upload-image', props.gallery?.id));
                request.setRequestHeader('X-CSRF-TOKEN' , page.props.token);

                request.upload.onprogress = (e) => {
                    progress(e.lengthComputable, e.loaded, e.total);
                };

                request.onload = function() {
                    if (request.status >= 200 && request.status < 300) {
                        props.gallery.image.push(request.responseText)
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
        files: props.gallery.image
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
                    
                    <FwbSelect
                        v-model="form.section"
                        :options="gallerySection"
                        :label="lang().label.section"
                    />

                    <InputError class="mt-2" :message="form.errors.section" />
                    
                </div>
                <div class="my-6 space-y-4">
                    
                    <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                    <InputError class="mt-2" :message="form.errors.title" />
                    
                </div>
                <div class="my-6 space-y-4">
                    <InputLabel for="product" :value="lang().label.product" />
                    <SelectInput
                        id="product"
                        class="mt-1 block w-full"
                        v-model="form.product_id"
                        :dataSet="product"
                    >
                    </SelectInput>
                    <InputError class="mt-2" :message="form.errors.product_id" />
                </div>
                <div class="my-6 space-y-4" v-if="!form.product_id">
                    <filepond
                        name="image"
                        ref="filepondRef"
                        label-idle="Upload Images..."
                        :allow-multiple="true"
                        accepted-file-types="image/jpeg, image/png"
						maxFileSize="1MB"
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
