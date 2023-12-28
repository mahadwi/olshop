<script setup>
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbFileInput } from 'flowbite-vue'
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    workWithUs: Array,
    workWithUsDetail: Array,
});

const emit = defineEmits(["close"]);

const formSection3 = useForm({
    work_with_us_id: null,
    title: "",
    title_en: "",
    description: "",
    description_en: "",
    image: "",
});

const createSection3 = () => {
    formSection3.post(route("work-with-us.storeSection3"), {
        preserveScroll: true,
        onSuccess: () => null,
        onError: () => null,
        onFinish: () => null,
    });
};

watchEffect(() => {
    const section3 = props.workWithUsDetail.find((item) => item.section == 3);
    formSection3.work_with_us_id = props.workWithUs[0]?.id;
    formSection3.title = section3?.title;
    formSection3.title_en = section3?.title_en;
    formSection3.description = section3?.description;
    formSection3.description_en = section3?.description_en;
    formSection3.image = section3?.image_url;
});
</script>

<template>
    <div class="dividenTitle">
        <h3 class="headerTitleText">Section 3</h3>
    </div>
    <div class="p-6">
        <form @submit.prevent="createSection3">
            <div class="my-6 space-y-4">
                <input type="hidden" v-model="formSection3.authentication_id">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <FwbInput v-model="formSection3.title" :placeholder="lang().label.title"
                            :label="lang().label.title" />
                        <InputError class="mt-2" :message="formSection3.errors.title" />
                    </div>
                    <div>
                        <FwbInput v-model="formSection3.title_en" :placeholder="lang().label.title_en"
                            :label="lang().label.title_en" />
                        <InputError class="mt-2" :message="formSection3.errors.title_en" />
                    </div>
                </div>
                <div>
                    <FwbFileInput accept="image/*" v-model="formSection3.image" :label="lang().label.image" />
                    <InputError class="mt-2" :message="formSection3.errors.image" />
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        {{ lang().label.description }} </label>
                    <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description"
                        v-model:content="formSection3.description" />
                    <InputError class="mt-2" :message="formSection3.errors.description" />
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                        {{ lang().label.description_en }} </label>
                    <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en"
                        v-model:content="formSection3.description_en" />
                    <InputError class="mt-2" :message="formSection3.errors.description_en" />
                </div>
                <div class="flex justify-center">
                    <SecondaryButton :disabled="formSection3.processing" @click="emit('close')">
                        {{ lang().button.close }}
                    </SecondaryButton>
                    <PrimaryButton type="submit" class="ml-3" :class="{ 'opacity-25': formSection3.processing }"
                        :disabled="formSection3.processing">
                        {{
                            formSection3.processing
                            ? lang().button.save + "..."
                            : lang().button.save
                        }}
                    </PrimaryButton>
                </div>
            </div>
        </form>
    </div>
</template>

<style scoped>
.card-space {
    margin-top: 50px;
}

.dividenTitle {
    width: 100%;
    height: 48px;
    flex-shrink: 0;
    background: #B9B9B9;
}

.headerTitleText {
    color: #FFF;
    text-align: center;
    font-family: Poppins;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    padding-top: 10px;
}
</style>
