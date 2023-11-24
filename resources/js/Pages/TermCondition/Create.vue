<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue'
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
});

const emit = defineEmits(["close"]);

const form = useForm({
    title: "",
    description: "",
    cp: "",
    image: "",
});

const create = () => {
    form.post(route("term-condition.store"), {
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
                        <FwbInput v-model="form.title" :placeholder="lang().label.title" :label="lang().label.title" />
                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div>
                        <FwbFileInput accept="image/*" v-model="form.image" :label="lang().label.image" />

                        <InputError class="mt-2" :message="form.errors.image" />
                    </div>

                    <div>
                        <FwbTextarea rows="4" :placeholder="lang().label.description" v-model="form.description" :label="lang().label.description" />
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
