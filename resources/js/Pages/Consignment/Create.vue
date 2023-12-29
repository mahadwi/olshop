<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput, FwbTab, FwbTabs} from 'flowbite-vue'
import { useForm } from "@inertiajs/vue3";
import { watchEffect, ref, onMounted } from "vue";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    show: Boolean,
    title: String,
    consignment: Array,
    consignmentDetail: Array,
});

const activeTab = ref('section1');
const emit = defineEmits(["close"]);

const addForm = () => {
    form.cardsSection4.push({
        id: null,
        title: "",
        title_en: "",
        description: "",
        description_en: "",
        image: "",
    });
};

const deleteForm = (index) => {
    form.cardsSection4.splice(index, 1);
};

const form = useForm({
    consignment_id:"",

    // HEADER TITLE
    title: "",
    title_en: "",
    description: "",
    description_en: "",

    // SECTION 1
    titleSection1:"",
    titleEnSection1:"",
    descriptionSection1:"",
    descriptionEnSection1:"",
    imageSection1:"",

    // SECTION 2
    titleSection2:"",
    titleEnSection2:"",
    descriptionSection2:"",
    descriptionEnSection2:"",
    linkSection2:"",

    // SECTION 2
    titleSection4:"",
    titleEnSection4:"",
    imageSection4:"",
    cardsSection4: [],
});

onMounted(() => {
    const section4 = props.consignmentDetail.find((item) => item.section == 4);

    if(section4.consignment_card.length == 0){
        addForm();
    }
});

const create = () => {
    form.post(route("consignment.store"), {
        preserveScroll: true,
        onSuccess: () => null,
        onError: () => null,
        onFinish: () => null,
    });
};

const createSection1 = () => {
    form.post(route("consignment.storeSection1"), {
        preserveScroll: true,
        onSuccess: () => null,
        onError: () => null,
        onFinish: () => null,
    });
};

const createSection2 = () => {
    form.post(route("consignment.storeSection2"), {
        preserveScroll: true,
        onSuccess: () => null,
        onError: () => null,
        onFinish: () => null,
    });
};

const createSection4 = () => {
    form.post(route("consignment.storeSection4"), {
        preserveScroll: true,
        onSuccess: () => null,
        onError: () => null,
        onFinish: () => null,
    });
};

watchEffect(() => {
    if (props.show) {
        form.consignment_id = props.consignment[0]?.id;
        form.title = props.consignment[0]?.title;
        form.title_en = props.consignment[0]?.title_en;
        form.description = props.consignment[0]?.description;
        form.description_en = props.consignment[0]?.description_en;

        form.titleSection1 = props.consignmentDetail[0]?.title;
        form.titleEnSection1 = props.consignmentDetail[0]?.title_en;
        form.descriptionSection1 = props.consignmentDetail[0]?.description;
        form.descriptionEnSection1 = props.consignmentDetail[0]?.description_en;
    
        form.errors = {};

        form.titleSection2 = props.consignmentDetail[1]?.title;
        form.titleEnSection2 = props.consignmentDetail[1]?.title_en;
        form.descriptionSection2 = props.consignmentDetail[1]?.description;
        form.descriptionEnSection2 = props.consignmentDetail[1]?.description_en;
        form.linkSection2 = props.consignmentDetail[1]?.link;

        const section4 = props.consignmentDetail.find((item) => item.section == 4);
        form.titleSection4 = section4?.title;
        form.titleEnSection4 = section4?.title_en;

        if(section4?.consignment_card.length > 0) {
            form.cardsSection4 = section4?.consignment_card;
        }
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
                     <div>
                        <fwb-tabs v-model="activeTab" variant="pills" class="p-5">
                            <fwb-tab name="section1" title="Section 1">
                                <div class="dividenTitle">
                                    <h3 class="headerTitleText">Section 1</h3>
                                </div>
                                <div class="p-6">
                                    <form @submit.prevent="createSection1">
                                        <div class="my-6 space-y-4">
                                            <input type="hidden" v-model="form.consignment_id">
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
                                    <form @submit.prevent="createSection2">
                                        <div class="my-6 space-y-4">
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
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                                                <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.descriptionSection2" />
                                                <InputError class="mt-2" :message="form.errors.descriptionSection2" />
                                            </div>

                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                                                <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.descriptionEnSection2" />
                                                <InputError class="mt-2" :message="form.errors.descriptionEnSection2" />
                                            </div>

                                            <div>
                                                <FwbInput v-model="form.linkSection2" :placeholder="lang().label.link" :label="lang().label.link" />
                                                <InputError class="mt-2" :message="form.errors.linkSection2" />
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
                            <fwb-tab name="section4" title="Section 4">
                                <div class="dividenTitle">
                                    <h3 class="headerTitleText">Section 4</h3>
                                </div>
                                <div class="p-6">
                                    <form @submit.prevent="createSection4">
                                        <div class="my-6 space-y-4">
                                            <!-- <input type="hidden" v-model="form.work_with_us_id"> -->
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <FwbInput v-model="form.titleSection4" :placeholder="lang().label.title" :label="lang().label.title" />
                                                    <InputError class="mt-2" :message="form.errors.titleSection4" />
                                                </div>
                                                <div>
                                                    <FwbInput v-model="form.titleEnSection4" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                                                    <InputError class="mt-2" :message="form.errors.titleEnSection4" />
                                                </div>
                                            </div>
                                            <div>
                                                <FwbFileInput accept="image/*" v-model="form.imageSection4" :label="lang().label.image" />
                                                <InputError class="mt-2" :message="form.errors.imageSection4" />
                                            </div>
                                            <hr>
                                            <div v-for="(item, index) in form.cardsSection4" :key="index">
                                                <input type="hidden" v-model="item.id" >
                                                <div class="mb-5 card-space">
                                                    Card {{ index+1 }}
                                                    <button @click="deleteForm" type="button" class="btn-danger">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                        {{ lang().tooltip.delete }}
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <FwbInput v-model="item.title" :placeholder="lang().label.title" :label="lang().label.title" />
                                                        <InputError class="mt-2" :message="form.errors[`cardsSection4.${index}.title`]" />
                                                    </div>
                                                    <div>
                                                        <FwbInput v-model="item.title_en" :placeholder="lang().label.title_en" :label="lang().label.title_en" />
                                                        <InputError class="mt-2" :message="form.errors[`cardsSection4.${index}.title_en`]" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <FwbFileInput accept="image/*" v-model="item.image" :label="lang().label.image" />
                                                    <InputError class="mt-2" :message="form.errors[`cardsSection4.${index}.image`]" />
                                                </div>

                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                                                    <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="item.description" />
                                                    <InputError class="mt-2" :message="form.errors[`cardsSection4.${index}.description`]" />
                                                </div>

                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                                                    <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="item.description_en" />
                                                    <InputError class="mt-2" :message="form.errors[`cardsSection4.${index}.description_en`]" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-center">
                                            <SecondaryButton
                                                :disabled="form.processing"
                                                @click="emit('close')"
                                            >
                                                {{ lang().button.close }}
                                            </SecondaryButton>
                                            <SecondaryButton
                                                :disabled="form.processing"
                                                @click="addForm"
                                            >
                                                {{ lang().button.add_card }}
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
