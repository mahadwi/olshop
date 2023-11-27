<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    categories: Object,
    vendors: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    brand: "",
    product_category_id: "",
    user_id: "",
    stock: "",
    image: "",
    price: "",
    entry_date: "",
    expired_date: "",
    description: "",
});

const create = () => {
    form.post(route("product.store"), {
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

const categories = props.categories?.map((role) => ({
    label: role.name,
    value: role.id,
}));

const vendors = props.vendors?.map((role) => ({
    label: role.name,
    value: role.id,
}));
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
                        <InputLabel for="name" :value="lang().placeholder.name" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            :placeholder="lang().placeholder.name"
                            :error="form.errors.name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel for="brand" :value="lang().label.brand" />
                        <TextInput
                            id="brand"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.brand"
                            :placeholder="lang().label.brand"
                            :error="form.errors.brand"
                        />
                        <InputError class="mt-2" :message="form.errors.brand" />
                    </div>
                    <div>
                        <InputLabel for="product_category" :value="lang().label.product_category" />
                        <SelectInput
                            id="product_category"
                            class="mt-1 block w-full"
                            v-model="form.product_category_id"
                            required
                            :dataSet="categories"
                        >
                        </SelectInput>
                        <InputError class="mt-2" :message="form.errors.product_category_id" />
                    </div>
                    <div>
                        <InputLabel for="vendor" :value="lang().label.vendor" />
                        <SelectInput
                            id="vendor"
                            class="mt-1 block w-full"
                            v-model="form.vendor"
                            required
                            :dataSet="vendors"
                        >
                        </SelectInput>
                        <InputError class="mt-2" :message="form.errors.vendor_id" />
                    </div>
                    <div>
                        <InputLabel for="stock" :value="lang().label.stock" />
                        <TextInput
                            id="stock"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.stock"
                            :placeholder="lang().label.stock"
                            :error="form.errors.stock"
                        />
                        <InputError class="mt-2" :message="form.errors.stock" />
                    </div>
                    <div>
                        <InputLabel for="price" :value="lang().label.price" />
                        <TextInput
                            id="price"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.price"
                            :placeholder="lang().label.price"
                            :error="form.errors.price"
                        />
                        <InputError class="mt-2" :message="form.errors.price" />
                    </div>
                    <div>
                        <InputLabel for="entry_date" :value="lang().label.entry_date" />
                        <TextInput
                            id="entry_date"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.entry_date"
                            :placeholder="lang().label.entry_date"
                            :error="form.errors.entry_date"
                        />
                        <InputError class="mt-2" :message="form.errors.entry_date" />
                    </div>
                    <div>
                        <InputLabel for="expired_date" :value="lang().label.expired_date" />
                        <TextInput
                            id="expired_date"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.expired_date"
                            :placeholder="lang().label.expired_date"
                            :error="form.errors.expired_date"
                        />
                        <InputError class="mt-2" :message="form.errors.expired_date" />
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
