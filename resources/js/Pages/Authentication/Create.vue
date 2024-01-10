<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput, FwbTab, FwbTabs} from 'flowbite-vue'
import { useForm } from "@inertiajs/vue3";
import { watchEffect, ref } from "vue";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    show: Boolean,
    title: String,
    authentication: Array,
    authenticationDetail: Array,
});

const activeTab = ref('section1');
const emit = defineEmits(["close"]);

const form = useForm({
    authentication_id: "",

    title: "",
    title_en: "",
    description: "",
    description_en: "",
    no_hp: "",
    link: "",

    // SECTION 1
    titleSection1:"",
    titleEnSection1:"",
    descriptionSection1:"",
    descriptionEnSection1:"",
    imageSection1:"",
    imageUrlSection1:"",

    // SECTION 2
    titleSection2:"",
    titleEnSection2:"",
    descriptionSection2:"",
    descriptionEnSection2:"",
    imageSection2:"",
    imageUrlSection2:"",

    // SECTION 3
    titleSection3:"",
    titleEnSection3:"",
    descriptionSection3:"",
    descriptionEnSection3:"",
    imageSection3:"",
    imageUrlSection3:"",
});

const create = () => {
    form.post(route("authentication.store"), {
        preserveScroll: true,
        onSuccess: () => null,
        onError: () => null,
        onFinish: () => null,
    });
};

const createSection = (section) => {
    form.post(route(`authentication.storeSection${section}`), {
        preserveScroll: true,
        onSuccess: () => null,
        onError: () => null,
        onFinish: () => null,
    });
};

watchEffect(() => {
    if (props.show) {
        form.authentication_id = props.authentication[0]?.id;
        form.title = props.authentication[0]?.title;
        form.title_en = props.authentication[0]?.title_en;
        form.description = props.authentication[0]?.description;
        form.description_en = props.authentication[0]?.description_en;
        form.no_hp = props.authentication[0]?.no_hp;
        form.link = props.authentication[0]?.link;

        const section1 = props.authenticationDetail .find((item) => item.section == 1);
        form.titleSection1 = section1?.title;
        form.titleEnSection1 = section1?.title_en;
        form.descriptionSection1 = section1?.description;
        form.descriptionEnSection1 = section1?.description_en;
        form.imageUrlSection1 = section1?.image_url;

        const section2 = props.authenticationDetail .find((item) => item.section == 2);
        form.titleSection2 = section2?.title;
        form.titleEnSection2 = section2?.title_en;
        form.descriptionSection2 = section2?.description;
        form.descriptionEnSection2 = section2?.description_en;
        form.imageUrlSection2 = section2?.image_url;

        const section3 = props.authenticationDetail .find((item) => item.section == 3);
        form.titleSection3 = section3?.title;
        form.titleEnSection3 = section3?.title_en;
        form.descriptionSection3 = section3?.description;
        form.descriptionEnSection3 = section3?.description_en;
        form.imageUrlSection3 = section3?.image_url;
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
            <h2
                class="p-6 text-lg font-medium text-slate-900 dark:text-slate-100"
            >
                {{ lang().label.add }} {{ props.title }}
            </h2>
            <div class="p-6 my-6 space-y-4">
                <form @submit.prevent="create">
                    <div class="mb-5 grid grid-cols-2 gap-4">
                        <div>
                            <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        <div>
                            <FwbInput v-model="form.title_en" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                            <InputError class="mt-2" :message="form.errors.title_en" />
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.description_en" />
                        <InputError class="mt-2" :message="form.errors.description_en" />
                    </div>

                    <div class="mb-5">
                        <FwbInput v-model="form.no_hp" :placeholder="lang().label.no_hp" :label="lang().label.no_hp" />
                        <InputError class="mt-2" :message="form.errors.no_hp" />
                    </div>

                    <div class="mb-5">
                        <FwbInput v-model="form.link" :placeholder="lang().label.link" :label="lang().label.link" />
                        <InputError class="mt-2" :message="form.errors.link" />
                    </div>
                    <div class="flex justify-center">
                        <PrimaryButton
                            type="submit"
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            {{
                                form.processing
                                    ? lang().button.save + "..."
                                    : lang().button.save
                            }}
                        </PrimaryButton>
                    </div>
                </form>
                <hr>
                <div v-if="props.authentication.length > 0">
                    <fwb-tabs v-model="activeTab" variant="pills" class="p-5">
                        <fwb-tab name="section1" title="Section 1">
                            <div class="dividenTitle">
                                <h3 class="headerTitleText">Section 1</h3>
                            </div>
                            <div class="p-6">
                                <form @submit.prevent="createSection('1')">
                                    <div class="my-6 space-y-4">
                                        <input type="hidden" v-model="form.authentication_id">
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
                                            <img :src="form.imageUrlSection1" style="width:30%">
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
                                    <div class="flex justify-center">
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
                                                    ? lang().button.save + "..."
                                                    : lang().button.save
                                            }}
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </div>
                        </fwb-tab>
                        <fwb-tab name="section2" title="Section 2">
                            <div class="dividenTitle">
                                <h3 class="headerTitleText">Section 2</h3>
                            </div>
                            <div class="p-6">
                                <form @submit.prevent="createSection('2')">
                                    <div class="my-6 space-y-4">
                                        <input type="hidden" v-model="form.authentication_id" >
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <FwbInput v-model="form.titleSection2" :placeholder="lang().label.title" :label="lang().label.title" />
                                                <InputError class="mt-2 section2" :message="form.errors.titleSection2" />
                                            </div>
                                            <div>
                                                <FwbInput v-model="form.titleEnSection2" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                                                <InputError class="mt-2 section2" :message="form.errors.titleEnSection2" />
                                            </div>
                                        </div>
                                        <div>
                                            <FwbFileInput accept="image/*" v-model="form.imageSection2" :label="lang().label.image" />
                                            <InputError class="mt-2" :message="form.errors.imageSection2" />
                                        </div>
                                        <div>
                                            <img :src="form.imageUrlSection2" style="width:30%">
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
                                    <div class="flex justify-center">
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
                                                    ? lang().button.save + "..."
                                                    : lang().button.save
                                            }}
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </div>
                        </fwb-tab>
                        <fwb-tab name="section3" title="Section 3">
                            <div class="dividenTitle">
                                <h3 class="headerTitleText">Section 3</h3>
                            </div>
                            <div class="p-6">
                                <form @submit.prevent="createSection('3')">
                                    <div class="my-6 space-y-4">
                                        <input type="hidden" v-model="form.authentication_id" >
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
                                            <img :src="form.imageUrlSection3" style="width:30%">
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
                                    <div class="flex justify-center">
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
                                                    ? lang().button.save + "..."
                                                    : lang().button.save
                                            }}
                                        </PrimaryButton>
                                    </div>
                                </form>
                            </div>
                        </fwb-tab>
                    </fwb-tabs>
                </div>
            </div>
        </Modal>
    </section>
</template>
