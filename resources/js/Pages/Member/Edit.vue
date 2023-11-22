<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { FwbInput } from 'flowbite-vue'

const props = defineProps({
    show: Boolean,
    title: String,
    type: Object,
    vendor: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    email: "",
    no_hp: "",
    vendor_type:"",
    bank: "",
    bank_account_holder: "",
    bank_account_number: "",
    ktp: "",
    address: "",
});

const update = () => {
    form.put(route("vendor.update", props.vendor?.id), {
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
        form.name = props.vendor?.name;
        form.email = props.vendor?.email;
        form.no_hp = props.vendor?.no_hp;
        form.bank = props.vendor?.bank;
        form.vendor_type = props.vendor?.vendor_type;
        form.bank_account_holder = props.vendor?.bank_account_holder;
        form.bank_account_number = props.vendor?.bank_account_number;
        form.ktp = props.vendor?.ktp;
        form.address = props.vendor?.address;
        form.errors = {};
    }
});


const type = Object.values(props.type).map((data) => ({
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
                        <InputLabel for="name" :value="lang().label.name" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full cursor-not-allowed"
                            disabled
                            v-model="form.name"                            
                            :placeholder="lang().placeholder.name"
                            :error="form.errors.name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="email" :value="lang().label.email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full cursor-not-allowed"
                            disabled
                            v-model="form.email"
                            :placeholder="lang().placeholder.email"
                            :error="form.errors.email"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>
                    <div>
                        <InputLabel for="ktp" :value="lang().label.ktp" />
                        <TextInput
                            id="ktp"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.ktp"
                            :error="form.errors.ktp"
                        />
                        <InputError class="mt-2" :message="form.errors.ktp" />
                    </div>
                    <div>
                        <InputLabel for="no_hp" :value="lang().label.no_hp" />
                        <TextInput
                            id="no_hp"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.no_hp"
                            :error="form.errors.no_hp"
                        />
                        <InputError class="mt-2" :message="form.errors.no_hp" />
                    </div>
                    <div>
                        <InputLabel for="type" :value="lang().label.type" />
                        <SelectInput
                            id="type"
                            class="mt-1 block w-full"
                            v-model="form.vendor_type"
                            required
                            :dataSet="type"
                        >
                        </SelectInput>
                        <InputError class="mt-2" :message="form.errors.vendor_type" />
                    </div>
                    <div>
                        <InputLabel
                            for="bank"
                            :value="lang().label.bank"
                        />
                        <TextInput
                            id="bank"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.bank"
                            :placeholder="lang().placeholder.bank"
                            :error="form.errors.bank"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.bank"
                        />
                    </div>
                    <div>
                        <InputLabel
                            for="bank_account_number"
                            :value="lang().label.bank_account_number"
                        />
                        <TextInput
                            id="bank_account_number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.bank_account_number"
                            :placeholder="lang().placeholder.bank_account_number"
                            :error="form.errors.bank_account_number"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.bank_account_number"
                        />
                    </div>
                    <div>
                        <InputLabel
                            for="bank_account_holder"
                            :value="lang().label.bank_account_holder"
                        />
                        <TextInput
                            id="bank_account_holder"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.bank_account_holder"
                            :placeholder="lang().placeholder.bank_account_holder"
                            :error="form.errors.bank_account_holder"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.bank_account_holder"
                        />
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
