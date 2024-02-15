<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { priceFormat } from '../../helper.js'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Multiselect from "@vueform/multiselect";

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
	FwbCheckbox
} from "flowbite-vue";
import DatePicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";


const props = defineProps({
  title: String,
  breadcrumbs: Object,
  commission: Object,
  brands: Object,
  categories: Object,
});

const form = useForm({
  brand_id: props.commission.brand_id,
  category_id:props.commission.category_id,
  details:props.commission.details
});

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const update = () => {
    form.put(route("commission.update", props.commission?.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

const brands = props.brands.map((data) => ({
    label: data.name,
    value: data.id
}))

const categories = props.categories.map((data) => ({
    label: data.name,
    value: data.id
}))

const addDetail = () => {
    form.details.push({
       min:0,
       max:0,
       percent:0,
    });
};

const deleteDetail = (index) => {
    form.details.splice(index, 1);
};

const formatAngka = (event, type, index) => {
  let angka = parseFloat(event.target.value.replace(/[^\d]/g, "")) || 0;

  let bilangan = String(angka);
  let hasil = "";
  let count = 0;

  for (let i = bilangan.length - 1; i >= 0; i--) {
    hasil = bilangan[i] + hasil;
    count++;

    if (count === 3 && i > 0) {
      hasil = "." + hasil;
      count = 0;
    }
  }

  form.details[index][type] = hasil;
};

  
onMounted(() => {
    // addDetail();
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
          <h3 class="mb-4 text-xl font-semibold dark:text-white">
            {{ props.title }}
          </h3>
          <form @submit.prevent="update">
            <div class="grid grid-cols-12 gap-6">
              <div class="col-span-4">
                <InputLabel for="brand" :value="lang().label.brand" />

                <Multiselect
                  id="brand"
                  v-model="form.brand_id"
                  :options="brands"
                  track-by="label"
                  label="label"
                  :searchable="true"
                  placeholder="Select"
                />

                <InputError class="mt-2" :message="form.errors.brand_id" />
              </div>
              <div class="col-span-8">
                <InputLabel for="category" :value="lang().label.category" />

                <Multiselect
                  mode="tags"
                  id="category"
                  v-model="form.category_id"
                  :options="categories"
                  track-by="label"
                  label="label"
                  :searchable="true"
                  placeholder="Select"
                />

                <InputError class="mt-2" :message="form.errors.category_id" />
              </div>
              <div class="col-span-12">
                <button @click="addDetail"
                  class="btn-primary mb-2" type="button">
                      {{ 'Add Detail Price' }}
                </button>
              </div>
              <template v-for="(item, index) in form.details" :key="index">            
                <div class="col-span-4">
                  <FwbInput v-model="item.min" @input="formatAngka($event, 'min', index)" :placeholder="lang().label.min_price" :label="lang().label.min_price" />
                  <InputError class="mt-2" :message="form.errors[`details.${index}.min`]" />
                </div>
                <div class="col-span-4">
                  <FwbInput v-model="item.max" @input="formatAngka($event, 'max', index)" :placeholder="lang().label.max_price" :label="lang().label.max_price" />
                  <InputError class="mt-2" :message="form.errors[`details.${index}.max`]" />          
                </div>
                <div class="col-span-3">
                  <FwbInput v-model="item.percent" :placeholder="lang().label.percent" :label="lang().label.percent" />
                  <InputError class="mt-2" :message="form.errors[`details.${index}.percent`]" />             
                </div>
                <div class="col-span-1">
                  <fwb-button v-if="index != 0" color="red" class="mt-7" pill square @click="deleteDetail(index)"  type="button">
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                  </fwb-button>            
                </div>
              </template>
							
              <div class="flex justify-start gap-2 col-span-6 sm:col-full">
                <PrimaryButton
                  type="submit"
                  :class="{
                    'opacity-25': form.processing,
                  }"
                  :disabled="form.processing"
                >
                  {{
                    form.processing
                      ? lang().button.update + "..."
                      : lang().button.update
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

<style src="@vueform/multiselect/themes/default.css"></style>