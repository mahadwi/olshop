<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    returnPolice: Object,
});
console.log(props.returnPolice);
const emit = defineEmits(["close"]);

const form = useForm({
    title: "",
    description: "",
    cp: "",
    image: "",
});

const update = () => {
    form.post(route("return-police.update", props.returnPolice?.id), {
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
        form.title = props.returnPolice?.title;
        form.description = props.returnPolice?.description;
        form.cp = props.returnPolice?.cp;
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
                        <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div>
                        <FwbFileInput accept="image/*" v-model="form.image" :label="lang().label.image" />
                        <InputError class="mt-2" :message="form.errors.image" />
                    </div>

                    <div>
                        <label class="">{{lang().label.description}}</label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div>
                        <FwbInput v-model="form.cp" :placeholder="lang().label.cp" :label="lang().label.cp" />
                        <InputError class="mt-2" :message="form.errors.cp" />
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
