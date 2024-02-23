<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput, FwbSelect } from 'flowbite-vue'

import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import Rating from 'primevue/rating';
import 'primevue/resources/themes/lara-light-blue/theme.css'
import { dataSwitch } from '../../helper.js'

const props = defineProps({
    show: Boolean,
    title: String,
    review: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    review: "",
    rating: 0,    
    image: "",
    is_display: "",
});

const update = () => {
    form.post(route("review.update", props.review?.id), {
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
        form.name = props.review?.name;
        form.review = props.review?.review;
        form.rating = props.review?.rating;
        form.is_display = props.review?.is_display;
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
                    {{ lang().label.image_review }}
                </h2>
                <div class="my-6 space-y-4">
                    <div>
                        <FwbInput readonly v-model="props.review.product.name" :placeholder="lang().label.product" :label="lang().label.product" />
                    </div>
                    
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.image_review}} </label>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">


                        <div v-for="(image, index) in props.review.imageable"
                                            :key="index">
                            <img class="h-auto max-w-full rounded-lg" :src="`/image/review/`+image.name"  alt="">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <SecondaryButton
                        :disabled="form.processing"
                        @click="emit('close')"
                    >
                        {{ lang().button.close }}
                    </SecondaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
