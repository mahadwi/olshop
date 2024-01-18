<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed, watchEffect, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { priceFormat } from '../../helper.js'

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

import {
  FwbTextarea, FwbFileInput, FwbInput,
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
  FwbButton,
  FwbSelect
} from "flowbite-vue";
import DatePicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";

import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const props = defineProps({
  title: String,
  breadcrumbs: Object,
  product: Object,
  vendorProductStatus:Object
});

const form = useForm({
    confirm_date: "",
    status: "",
});

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const vendorProductStatus = Object.values(props.vendorProductStatus).map((data) => ({
    name: data,
    value: data
}))

const update = () => {
    form.put(route("konsinyasi.update", props.product?.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

onMounted(() => {
  form.status = props.product?.status;
  form.confirm_date = props.product?.confirm_date;
  form.errors = {};
});


</script>

<template>
  <Head :title="props.title" />
  <AuthenticatedLayout>
    <Breadcrumb :breadcrumbs="breadcrumbs" /> 

    <div
      class="grid grid-cols-1 mb-10 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900"
    >
      <div class="col-span-3">
        <div
          class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800"
        >
          <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">          
              <h3 class="text-xl font-semibold dark:text-white">
                {{ lang().label.vendor }}
              </h3>
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.vendor.name"
                :placeholder="lang().label.name"
                :label="lang().label.name"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.vendor.bank_account_holder"
                :placeholder="lang().label.bank_account_holder"
                :label="lang().label.bank_account_holder"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.vendor.ktp"
                :placeholder="lang().label.ktp"
                :label="lang().label.ktp"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.vendor.bank_account_number"
                :placeholder="lang().label.bank_account_number"
                :label="lang().label.bank_account_number"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.vendor.bank"
                :placeholder="lang().label.bank"
                :label="lang().label.bank"
              />
            </div>
            <div class="col-span-12">          
              <h3 class="text-xl font-semibold dark:text-white">
                {{ lang().label.product }}
              </h3>
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.name"
                :placeholder="lang().placeholder.name"
                :label="lang().placeholder.name"
              />
            </div>
            
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.brand.name"
                :placeholder="lang().label.brand"
                :label="lang().label.brand"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.product_category.name"
                :placeholder="lang().label.product_category"
                :label="lang().label.product_category"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.color.name"
                :placeholder="lang().label.color"
                :label="lang().label.color"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.weight"
                :placeholder="lang().label.weight"
                :label="lang().label.weight"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.length"
                :placeholder="lang().label.length"
                :label="lang().label.length"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.width"
                :placeholder="lang().label.width"
                :label="lang().label.width"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.height"
                :placeholder="lang().label.height"
                :label="lang().label.height"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.price"
                :placeholder="lang().label.price"
                :label="lang().label.price"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.price_usd"
                :placeholder="lang().label.price_usd"
                :label="lang().label.price_usd"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.commission_type"
                :placeholder="lang().label.commission_type"
                :label="lang().label.commission_type"
              />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.commission"
                :placeholder="lang().label.commission"
                :label="lang().label.commission"
              />
            </div>
            <div class="col-span-6">
              <FwbTextarea
                  rows="4" readonly
                  :placeholder="lang().label.description"
                  v-model="props.product.description"
                  :label="lang().label.description"
                />
            </div>
            <div class="col-span-6">
              <FwbTextarea
                  rows="4" readonly
                  :placeholder="lang().label.description_en"
                  v-model="props.product.description_en"
                  :label="lang().label.description_en"
                />
            </div>
            <div class="col-span-6">
              <FwbTextarea
                  rows="4" readonly
                  :placeholder="lang().label.history"
                  v-model="props.product.history"
                  :label="lang().label.history"
                />
            </div>
            <div class="col-span-6">
              <FwbTextarea
                  rows="4" readonly
                  :placeholder="lang().label.history_en"
                  v-model="props.product.history_en"
                  :label="lang().label.history_en"
                />
            </div>
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.product.condition"
                :placeholder="lang().label.condition"
                :label="lang().label.condition"
              />
            </div>

            <div class="col-span-6"></div>          

          </div>

          <div v-if="props.product.imageable.length > 0" class="grid grid-cols-2 mt-5 md:grid-cols-3 gap-4">
              <div v-for="(image, index) in props.product.imageable"
                        :key="index">
                  <img class="h-auto max-w-full rounded-lg" :src="`/image/`+image.name" alt="">
              </div>
          </div>

          <form @submit.prevent="update">
            <div class="grid grid-cols-12 gap-6 mt-5">
              <div class="col-span-6">
                <InputLabel
                  for="confirm_date"
                  :value="lang().label.confirm_date"
                />
                <vue-tailwind-datepicker
                  readonly
                  v-model="form.confirm_date"
                  :formatter="formatter"
                  :placeholder="lang().label.confirm_date"
                  as-single
                />
                <InputError class="mt-2" :message="form.errors.confirm_date" />
              </div>
              <div class="col-span-6">
                <FwbSelect
                    v-model="form.status"
                    :options="vendorProductStatus"
                    :label="lang().label.status"
                />

                <InputError class="mt-2" :message="form.errors.status" />
              </div>
              <div class="flex justify-start gap-2 col-span-6 sm:col-full">
                <PrimaryButton
                  type="submit"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  {{
                    form.processing
                      ? lang().button.save + "..."
                      : lang().button.save
                  }}
                </PrimaryButton>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

