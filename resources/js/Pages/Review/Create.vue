<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
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
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    review: "",
    rating: 0,    
    image: "",
    is_display: "",
});

const create = () => {
    form.post(route("review.store"), {
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
        form.errors = {};
    }
});

</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <form class="p-6" @submit.prevent="create">
                <h2
                    class="text-lg font-medium text-slate-900 dark:text-slate-100"
                >
                    {{ lang().label.add }} {{ props.title }}
                </h2>
                <div class="my-6 space-y-4">
                    <div>
                        <FwbInput v-model="form.name" :placeholder="lang().label.name" :label="lang().label.name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <fwb-file-input accept="image/*" v-model="form.image" :label="lang().label.image" />

                        <InputError class="mt-2" :message="form.errors.image" />
                    </div>
                    <div>
                        <InputLabel for="rating" :value="lang().label.rating" />
                        <Rating v-model="form.rating" :cancel="false" />
                        <InputError class="mt-2" :message="form.errors.rating" />
                    </div>
                    <div>
                        <FwbTextarea rows="2" :placeholder="lang().label.review" v-model="form.review" :label="lang().label.review" />
                        <InputError class="mt-2" :message="form.errors.review" />
                    </div>
                    <div>
						<FwbSelect
                            v-model="form.is_display"
                            :options="dataSwitch"
                            :label="lang().label.display_on_homepage"
						/>
                        <InputError class="mt-2" :message="form.errors.is_display" />

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
                        type="submit"
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? lang().button.add + "..."
                                : lang().button.add
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>
