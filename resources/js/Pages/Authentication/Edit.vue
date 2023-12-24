<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput, FwbTab, FwbTabs, FwbAvatar } from 'flowbite-vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from "axios";

import { useForm } from "@inertiajs/vue3";
import { watchEffect, ref } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    authentication: Object,
});

const activeTab = ref('section1');

const emit = defineEmits(["close"]);

const form = useForm({
    title: "",
    title_en: "",
    description: "",
    description_en: "",
    no_hp: "",
    link: "",

    authenticationDetailId_section1: "",
    titleSection1:"",
    titleEnSection1:"",
    descriptionSection1:"",
    descriptionEnSection1:"",
    imageSection1:"",
    imageSectionUrl1:"",

    authenticationDetailId_section2: "",
    titleSection2:"",
    titleEnSection2:"",
    descriptionSection2:"",
    descriptionEnSection2:"",
    imageSection2:"",
    imageSectionUrl2:"",

    authenticationDetailId_section3: "",
    titleSection3:"",
    titleEnSection3:"",
    descriptionSection3:"",
    descriptionEnSection3:"",
    imageSection3:"",
    imageSectionUrl3:"",
});

const update = () => {
    form.post(route("authentication.update", props.authentication?.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

const getSetAuthDetail = async(authentication_id) => {
    try {
        const {data} = await axios.get(`/authentication/get-detail?authentication_id=${authentication_id}`);
        data.forEach((v, i) => {
            form[`authenticationDetailId_section${v.section}`] = v.id;
            form[`titleSection${v.section}`] = v.title;
            form[`titleEnSection${v.section}`] = v.title_en;
            form[`descriptionSection${v.section}`] = v.description;
            form[`descriptionEnSection${v.section}`] = v.description_en;
            // form[`imageSection${v.section}`] = v.image;
            form[`imageSectionUrl${v.section}`] = v.image_url;
        });
    } catch (error) {
        console.log(error);
    }
}

watchEffect(() => {
    if (props.show) {
        form.reset();
        form.title = props.authentication?.title;
        form.title_en = props.authentication?.title_en;
        form.description = props.authentication?.description;
        form.description_en = props.authentication?.description_en;
        form.no_hp = props.authentication?.no_hp;
        form.link = props.authentication?.link;
        getSetAuthDetail(props.authentication?.id)
        form.errors = {};
    }
});

</script>
<style scoped>
.dividenTitle {
    width: 100%;height: 48px;flex-shrink: 0;background: #B9B9B9;
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
<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')" maxWidth="7xl">
            <form class="p-6" @submit.prevent="update">
                <h2
                    class="text-lg font-medium text-slate-900 dark:text-slate-100"
                >
                    {{ lang().label.edit }} {{ props.title }}
                </h2>
                <div class="my-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        <div>
                            <FwbInput v-model="form.title_en" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                            <InputError class="mt-2" :message="form.errors.title_en" />
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.description_en" />
                        <InputError class="mt-2" :message="form.errors.description_en" />
                    </div>
                    <div>
                        <FwbInput v-model="form.no_hp" :placeholder="lang().label.no_hp" :label="lang().label.no_hp" />
                        <InputError class="mt-2" :message="form.errors.no_hp" />
                    </div>
                    <div>
                        <FwbInput v-model="form.link" :placeholder="lang().label.link" :label="lang().label.link" />
                        <InputError class="mt-2" :message="form.errors.link" />
                    </div>
                    <hr>
                    <div>
                        <fwb-tabs v-model="activeTab" variant="pills" class="p-5">
                            <fwb-tab name="section1" title="Section 1">
                                <div class="dividenTitle">
                                    <h3 class="headerTitleText">Section 1</h3>
                                </div>
                                <div class="p-6">
                                    <div class="my-6 space-y-4">
                                        <input type="hidden" v-model="form.authenticationDetailId_section1" >
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <FwbInput v-model="form.titleSection1" :placeholder="lang().label.title" :label="lang().label.title" />
                                                <InputError class="mt-2" :message="form.errors.titleSection1" />
                                            </div>
                                            <div>
                                                <FwbInput v-model="form.titleEnSection1" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                                                <InputError class="mt-2" :message="form.errors.titleEnSection1" />
                                            </div>
                                        </div>
                                        <div>
                                            <FwbFileInput accept="image/*" v-model="form.imageSection1" :label="lang().label.image" />
                                            <InputError class="mt-2" :message="form.errors.imageSection1" />
                                        </div>
                                        <div>
                                            <img :src="form.imageSectionUrl1" style="width:30%">
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                                            <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.descriptionSection1" />
                                            <InputError class="mt-2" :message="form.errors.descriptionSection1" />
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                                            <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.descriptionEnSection1" />
                                            <InputError class="mt-2" :message="form.errors.descriptionEnSection1" />
                                        </div>


                                    </div>
                                </div>
                            </fwb-tab>
                            <fwb-tab name="section2" title="Section 2">
                                <div class="dividenTitle">
                                    <h3 class="headerTitleText">Section 2</h3>
                                </div>
                                <div class="p-6">
                                    <div class="my-6 space-y-4">
                                        <input type="hidden" v-model="form.authenticationDetailId_section2" >
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <FwbInput v-model="form.titleSection2" :placeholder="lang().label.title" :label="lang().label.title" />
                                                <InputError class="mt-2" :message="form.errors.titleSection2" />
                                            </div>
                                            <div>
                                                <FwbInput v-model="form.titleEnSection2" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                                                <InputError class="mt-2" :message="form.errors.titleEnSection2" />
                                            </div>
                                        </div>
                                        <div>
                                            <FwbFileInput accept="image/*" v-model="form.imageSection2" :label="lang().label.image" />
                                            <InputError class="mt-2" :message="form.errors.imageSection2" />
                                        </div>
                                        <div>
                                            <img :src="form.imageSectionUrl2" style="width:30%">
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                                            <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.descriptionSection2" />
                                            <InputError class="mt-2" :message="form.errors.descriptionSection2" />
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                                            <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.descriptionEnSection2" />
                                            <InputError class="mt-2" :message="form.errors.descriptionEnSection2" />
                                        </div>


                                    </div>
                                </div>
                            </fwb-tab>
                            <fwb-tab name="section3" title="Section 3">
                                <div class="dividenTitle">
                                    <h3 class="headerTitleText">Section 3</h3>
                                </div>
                                <div class="p-6">
                                    <div class="my-6 space-y-4">
                                        <input type="hidden" v-model="form.authenticationDetailId_section3" >
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <FwbInput v-model="form.titleSection3" :placeholder="lang().label.title" :label="lang().label.title" />
                                                <InputError class="mt-2" :message="form.errors.titleSection3" />
                                            </div>
                                            <div>
                                                <FwbInput v-model="form.titleEnSection3" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                                                <InputError class="mt-2" :message="form.errors.titleEnSection3" />
                                            </div>
                                        </div>
                                        <div>
                                            <FwbFileInput accept="image/*" v-model="form.imageSection3" :label="lang().label.image" />
                                            <InputError class="mt-2" :message="form.errors.imageSection3" />
                                        </div>
                                        <div>
                                            <img :src="form.imageSectionUrl3" style="width:30%">
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                                            <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.descriptionSection3" />
                                            <InputError class="mt-2" :message="form.errors.descriptionSection3" />
                                        </div>

                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                                            <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.descriptionEnSection3" />
                                            <InputError class="mt-2" :message="form.errors.descriptionEnSection3" />
                                        </div>


                                    </div>
                                </div>
                            </fwb-tab>
                        </fwb-tabs>
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
