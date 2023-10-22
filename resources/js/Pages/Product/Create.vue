<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { 
    Textarea, FileInput
} from 'flowbite-vue'


const props = defineProps({
  title: String,
  categories: Object,
  vendors: Object,
  brands: Object,
  breadcrumbs: Object,
});

const form = useForm({
    name: "",
    brand_id: "",
    description: "",
    product_category_id: "",
    user_id: "",
    stock: "",
    image: "",
    price: "",
    history: "",
    entry_date: "",
    expired_date: "",
    description: "",
});

const categories = props.categories?.map((role) => ({
    label: role.name,
    value: role.id,
}));

const vendors = props.vendors?.map((role) => ({
    label: role.name,
    value: role.id,
}));

const brands = props.brands?.map((role) => ({
    label: role.name,
    value: role.id,
}));

const formatter = ref({
  date: 'DD-MM-YYYY',
  month: 'MMM',
})

const create = () => {
    form.post(route("product.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};


</script>

<template>
  <Head :title="props.title" />
  <AuthenticatedLayout>
    <Breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="grid grid-cols-1 mb-10 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        <div class="col-span-2">
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800"
            >
                <h3 class="mb-4 text-xl font-semibold dark:text-white">
                    {{ props.title }}
                </h3>
                <form @submit.prevent="create">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">                           
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
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="brand" :value="lang().label.brand" />
                            <SelectInput
                                id="brand"
                                class="mt-1 block w-full"
                                v-model="form.brand_id"
                                :dataSet="brands"
                            >
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.brand_id" />
                        </div>
                        <div class="col-span-6">

                            <Textarea rows="4" :placeholder="lang().label.description" v-model="form.description" :label="lang().label.description" />
                            <InputError class="mt-2" :message="form.errors.description" />

                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="entry_date" :value="lang().label.entry_date" />
                            <vue-tailwind-datepicker v-model="form.entry_date" :formatter="formatter" :placeholder="lang().label.entry_date"  as-single />
                            <InputError class="mt-2" :message="form.errors.entry_date" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="expired_date" :value="lang().label.expired_date" />
                            <vue-tailwind-datepicker v-model="form.expired_date" :formatter="formatter" :placeholder="lang().label.expired_date"  as-single />
                            <InputError class="mt-2" :message="form.errors.expired_date" />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="product_category" :value="lang().label.product_category" />
                            <SelectInput
                                id="product_category"
                                class="mt-1 block w-full"
                                v-model="form.product_category_id"
                                :dataSet="categories"
                            >
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.product_category_id" />
                        </div>
                       
                        <div class="col-span-6 sm:col-span-3">
                            <InputLabel for="vendor" :value="lang().label.vendor" />
                            <SelectInput
                                id="vendor"
                                class="mt-1 block w-full"
                                v-model="form.user_id"
                                :dataSet="vendors"
                            >
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.user_id" />
                        </div>                                        

                        <div class="col-span-6 sm:col-span-3">
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

                        <div class="col-span-6 sm:col-span-3">
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

                        <div class="col-span-6">

                            <Textarea rows="4" :placeholder="lang().label.history" v-model="form.history" :label="lang().label.history" />
                            <InputError class="mt-2" :message="form.errors.history" />
                            
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <FileInput accept="image/*" v-model="form.image" :label="lang().label.image" />

                            <InputError class="mt-2" :message="form.errors.image" />
                        </div>

                        <div class="flex justify-start gap-2 col-span-6 sm:col-full">                            
                            <PrimaryButton
                                type="submit"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                {{
                                    form.processing
                                        ? lang().button.add + "..."
                                        : lang().button.add
                                }}
                            </PrimaryButton>
                            <SecondaryButton
                                :disabled="form.processing"
                                @click="form.reset()"
                            >
                                Reset
                            </SecondaryButton>
                        </div>
                    </div>
                </form>
            </div>                   
        </div>
    </div>

    <!-- <div class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center mb-4 sm:mb-0">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">1-20</span> of <span class="font-semibold text-gray-900 dark:text-white">2290</span></span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="#" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    Previous
                </a>
                <a href="#" class="inline-flex items-center justify-center flex-1 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Next
                    <svg class="w-5 h-5 ml-1 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
        </div> -->
  </AuthenticatedLayout>
</template>

