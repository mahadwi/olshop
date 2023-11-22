<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { FwbInput, FwbFileInput } from 'flowbite-vue'

const bahasa = usePage().props.language.original;

const props = defineProps({
    show: Boolean,
    title: String,
    groupCoa: Object,
    normalBalance: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    code: "",
    name: "",
    normal_balance: "",
    is_active:""
});

const update = () => {
    form.put(route("group-coa.update", props.groupCoa?.id), {
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
        form.code = props.groupCoa?.code;
        form.name = props.groupCoa?.name;
        form.normal_balance = props.groupCoa?.normal_balance;
        form.is_active = props.groupCoa?.is_active;
        form.errors = {};
    }
});

const dataStatus = [
    {
        label: bahasa.label.active,
        value: true
    },
    {
        label: bahasa.label.not_active,
        value: false
    }    
];

const normalBalance = Object.values(props.normalBalance).map((data) => ({
    label: data,
    value: data
}))

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
                        <FwbInput v-model="form.code" :placeholder="lang().label.code" :label="lang().label.code" />
                        <InputError class="mt-2" :message="form.errors.code" />
                    </div>
                    
                    <div>
                        <InputLabel for="name" :value="lang().label.name" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            :placeholder="lang().placeholder.name"
                            :error="form.errors.name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel :for="lang().label.normal_balance" :value="lang().label.normal_balance" />
                        <SelectInput
                            :id="lang().label.normal_balance"
                            class="mt-1 block w-full"
                            v-model="form.normal_balance"
                            required
                            :dataSet="normalBalance"
                        >
                        </SelectInput>
                        <InputError class="mt-2" :message="form.errors.normal_balance" />
                    </div>
                    <div>
                        <InputLabel for="status" value="Status" />
                        <SelectInput
                            id="status"
                            class="mt-1 block w-full"
                            v-model="form.is_active"
                            required
                            :dataSet="dataStatus"
                        >
                        </SelectInput>
                        <InputError class="mt-2" :message="form.errors.is_active" />
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
