<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { FwbSelect, FwbInput } from 'flowbite-vue';
import { ref } from 'vue';

const props = defineProps({
    show: Boolean,
    title: String,
    promo: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    listEmailDetail: []
});

const listEmailDetail = ref([]);
const loadDetailEmail = async (promo_id=null) => {
    const response = await axios.get('/promo-subscribe/loadDetailEmail', {
        params:{
            promo_id
        }
    });
    listEmailDetail.value = response.data;
}

watchEffect(() => {
    if (props.show) {
        form.errors = {};
        loadDetailEmail(props.promo?.id);
    }
});

</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-center mb-3 text-slate-900 dark:text-slate-100"
                >
                    {{ lang().label.list_email }}
                </h2>
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
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <tr
                        v-for="(subscribe, index) in listEmailDetail"
                        :key="index"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <td class="tbl-column pl-4"> {{ ++index }}</td>
                            <td class="tbl-column"> {{ subscribe.name }}</td>
                            <td class="tbl-column"> {{ subscribe.email }}</td>
                            <td class="tbl-column"> {{ subscribe.status }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-end p-5">
                    <SecondaryButton
                        :disabled="form.processing"
                        @click="emit('close')"
                    >
                        {{ lang().button.close }}
                    </SecondaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
