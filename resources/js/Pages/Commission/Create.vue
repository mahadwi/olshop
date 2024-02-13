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
  brands: Object,
  categories: Object,
});

const form = useForm({
  brand_id: "",
  category_id:[],
});

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const create = () => {
  form.post(route("commission.store"), {
    preserveScroll: true,
    onSuccess: () => {
    },
    onError: () => null,
    onFinish: () => null,
  });
};

onMounted(() => {
    
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
          <form @submit.prevent="create">
            <div class="grid grid-cols-12 gap-6">
              <div class="col-span-4">
                <FwbInput
                  v-model="form.duration"
                  :placeholder="lang().label.duration"
                  :label="lang().label.duration"
                />
                <InputError class="mt-2" :message="form.errors.duration" />
              </div>
              <div class="col-span-8">

              </div>
							<div class="col-span-8">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">
                  {{ lang().label.operational }}
                </h3>
                <fwb-table>
                  <fwb-table-head>
                    <fwb-table-head-cell class="text-center">{{ lang().label.day }}</fwb-table-head-cell>
                    <fwb-table-head-cell class="text-center">{{ lang().label.time }}</fwb-table-head-cell>
                  </fwb-table-head>
                  <fwb-table-body>
                    <fwb-table-row v-for="(operational, index) in form.operationals"
                      :key="index">
                      <fwb-table-cell>
												<fwb-checkbox v-model="operational.is_open" :label="operational.day" />
											</fwb-table-cell>
                      <fwb-table-cell>
												<date-picker
													v-model:value="operational.time"
													range="true"
													format="HH:mm"
													value-type="format"
													type="time"
													placeholder="Select time"
												></date-picker>
											</fwb-table-cell>

                    </fwb-table-row>
                  </fwb-table-body>
                </fwb-table>
              </div>
							<template v-for="(operational, index) in form.operationals.data"
                 :key="index">
								
								<div class="col-span-4 mt-[-20px]">
									<fwb-checkbox v-model="check" label="Senin" />
								</div>
								<div class="col-span-6 mt-[-20px]">
									<date-picker
										v-model:value="form.time"
										range="true"
										format="HH:mm"
										value-type="format"
										type="time"
										placeholder="Select time"
									></date-picker>
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

