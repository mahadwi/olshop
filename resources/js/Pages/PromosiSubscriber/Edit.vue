<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput, FwbTab, FwbTabs, FwbCheckbox } from 'flowbite-vue'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { ref } from 'vue'

import { useForm } from "@inertiajs/vue3";
import { onMounted, watchEffect } from "vue";

const activeTab = ref('first')

const props = defineProps({
    show: Boolean,
    title: String,
    promo: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    title: "",
    message: "",
    listEmail: [],
    isCheckAll: false,
    isChecked: [],
});

const update = () => {
    form.post(route("promo-subscribe.update", props.promo?.id), {
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
        form.title = props.promo?.title;
        form.message = props.promo?.message;
        form.errors = {};
        loadEmail(props.promo?.id)
    }
});

    onMounted(async () => {
        // await loadEmail(props.promo?.id)
    });

const listEmail = ref([]);
const loadEmail = async (promo_id=null) => {
    const response = await axios.get('/promo-subscribe/loadEmail', {
        params:{
            promo_id
        }
    });
    listEmail.value = response.data;
}

const checkEmail = (e, emailId) => {
    if (e.target.checked) {
        form.isChecked.push(emailId);
    } else {
        form.isChecked.splice(form.isChecked.indexOf(emailId), 1);
    }
}

const checkAll = () => {

}

const handlePaneClick = () => {
    if(activeTab.value == 'first'){
        loadEmail(props.promo?.id);
    }else {

    }
}


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
                    <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                    <InputError class="mt-2" :message="form.errors.title" />
                </div>
                <div class="my-6 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.email_message}} </label>
                        <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.email_message" v-model:content="form.message" />
                        <InputError class="mt-2" :message="form.errors.message" />
                    </div>
                </div>

                <FwbTabs v-model="activeTab" class="p-5" @click:pane="handlePaneClick()">
                    <FwbTab name="first" :title="lang().label.get_email">
                        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700 mb-4">
                            <span></span>
                        </div>
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow">
                                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="tbl-head">
                                                No
                                                </th>
                                                <th scope="col" class="tbl-head">
                                                    {{ lang().label.name }}
                                                </th>
                                                <th scope="col" class="tbl-head">
                                                    {{ lang().label.email }}
                                                </th>
                                                <th scope="col" class="tbl-head text-center">
                                                    {{ lang().label.status }}
                                                </th>
                                                <th scope="col" class="tbl-head text-center">
                                                    {{ lang().label.action }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                            <tr
                                                v-for="(email, index) in listEmail.data"
                                                :key="index"
                                                class="hover:bg-gray-100 dark:hover:bg-gray-700"
                                            >
                                                <td class="tbl-column pl-4"> {{ ++index }}</td>
                                                <td class="tbl-column pl-4"> {{ email.name }}</td>
                                                <td class="tbl-column"> {{ email.email }}</td>
                                                <td class="tbl-column">{{ email.status }}</td>
                                                <td class="tbl-column text-center">
                                                    <div class="inline-block">
                                                        <fwb-checkbox @click="checkEmail($event, email.id)" />
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </FwbTab>
                </FwbTabs>

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
                                ? lang().button.save_send + "..."
                                : lang().button.save_send
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
