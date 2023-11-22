<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { FwbSelect, FwbInput } from 'flowbite-vue'

const props = defineProps({
    show: Boolean,
    title: String,
    normalBalance: Object,
    status: Object,
    groupCoa: Object,
    saldoAwal: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    code: "",
    name: "",
    normal_balance: "",
    group_coa_id: "",
    is_saldo_awal: "",
    status: "",
});

const create = () => {
    form.post(route("coa.store"), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

const normalBalance = Object.values(props.normalBalance).map((data) => ({
    name: data,
    value: data
}))

const status = Object.values(props.status).map((data) => ({
    name: data,
    value: data
}))

const groupCoa = props.groupCoa?.map((data) => ({
    name: data.name,
    value: data.id,
}));

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
                        <FwbInput v-model="form.code" :placeholder="lang().label.code" :label="lang().label.code" />
                        <InputError class="mt-2" :message="form.errors.code" />
                    </div>
                    
                    <div>
                        
                        <FwbInput v-model="form.name" :placeholder="lang().placeholder.name" :label="lang().placeholder.name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                        
                    </div>
                    <div>
                        <FwbSelect
                            v-model="form.group_coa_id"
                            :options="groupCoa"
                            :label="lang().label.group_coa"
                        />

                        <InputError class="mt-2" :message="form.errors.group_coa_id" />

                    </div>
                    <div>
                        <FwbSelect
                            v-model="form.normal_balance"
                            :options="normalBalance"
                            :label="lang().label.normal_balance"
                        />

                        <InputError class="mt-2" :message="form.errors.normal_balance" />

                    </div>

                    <div>
                        <FwbSelect
                            v-model="form.status"
                            :options="status"
                            :label="lang().label.status"
                        />

                        <InputError class="mt-2" :message="form.errors.status" />

                    </div>

                    <div>
                        <FwbSelect
                            v-model="form.is_saldo_awal"
                            :options="saldoAwal"
                            :label="lang().label.beginning_balance"
                        />

                        <InputError class="mt-2" :message="form.errors.is_saldo_awal" />

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
