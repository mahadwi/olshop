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
import CreateTicket from "@/Pages/Event/CreateTicket.vue";

import { 
  FwbTextarea, FwbFileInput, FwbInput,
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
  FwbButton
} from "flowbite-vue";
import DatePicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";

import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const props = defineProps({
  title: String,
  breadcrumbs: Object,
});

const data = reactive({
    createOpen: false,
    editOpen: false,
    deleteOpen: false,    
});

const form = useForm({
  name: "",
  place: "",
  start_date: "",
  end_date: "",
  time_start: "",
  time_end: "",
  maps:"",
  maps_address:"",
  cover:"",
  banner:"",  
  description: "",
  details: [

  ],
});

const dataSwitch = [
  {
    label: "Yes",
    value: true,
  },
  {
    label: "No",
    value: false,
  },
];

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const create = () => {
  form.post(route("event.store"), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};

const dataEvent = reactive({
  time_start:"",
  time_end:"",
});

const removeTicket = (index) => {
  // console.log(index);
  form.details.splice(index, 1);
}


watch(
  () => form,
  (newValue) => {
    dataEvent.time_start = newValue.time_start;
    dataEvent.time_end = newValue.time_end;
  },
  { deep: true }
)
</script>

<template>
  <Head :title="props.title" />
  <AuthenticatedLayout>
    <Breadcrumb :breadcrumbs="breadcrumbs" />

    <create-ticket
        :show="data.createOpen"
        @close="data.createOpen = false"
        title="Ticket"
        :dataEvent="dataEvent"
        :details="form.details"
    />

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
              <div class="col-span-6">
                <FwbInput
                  v-model="form.name"
                  :placeholder="lang().label.name"
                  :label="lang().label.name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
              </div>
              <div class="col-span-6">
                <FwbInput
                  v-model="form.place"
                  :placeholder="lang().label.place"
                  :label="lang().label.place"
                />
                <InputError class="mt-2" :message="form.errors.place" />
              </div>
              <div class="col-span-6">
                <InputLabel for="start_date" :value="lang().label.start_date" />
                <vue-tailwind-datepicker
                  v-model="form.start_date"
                  :formatter="formatter"
                  :placeholder="lang().label.start_date"
                  as-single
                />
                <InputError class="mt-2" :message="form.errors.start_date" />
              </div>
              <div class="col-span-6">
                <InputLabel for="end_date" :value="lang().label.end_date" />
                <vue-tailwind-datepicker
                  v-model="form.end_date"
                  :formatter="formatter"
                  :placeholder="lang().label.end_date"
                  as-single
                />
                <InputError class="mt-2" :message="form.errors.end_date" />
              </div>
              <div class="col-span-6">
                <InputLabel for="time_start" :value="lang().label.time_start" />
                <date-picker
                  v-model:value="form.time_start"
                  format="hh:mm A"
                  value-type="format"
                  type="time"
                  placeholder="Select time"
                ></date-picker>
                <InputError class="mt-2" :message="form.errors.time_start" />
              </div>
              <div class="col-span-6">
                <InputLabel for="time_end" :value="lang().label.time_end" />
                <date-picker
                  v-model:value="form.time_end"
                  format="hh:mm A"
                  value-type="format"
                  type="time"
                  placeholder="Select time"
                ></date-picker>
                <InputError class="mt-2" :message="form.errors.time_end" />
              </div> 
              <div class="col-span-6">
                <FwbInput
                  v-model="form.maps"
                  :placeholder="lang().label.maps"
                  :label="lang().label.maps"
                />
                <InputError class="mt-2" :message="form.errors.maps" />
              </div>

              <div class="col-span-6">
                <FwbInput
                  v-model="form.maps_address"
                  :placeholder="lang().label.maps_address"
                  :label="lang().label.maps_address"
                />
                <InputError class="mt-2" :message="form.errors.maps_address" />
              </div>

              <div class="col-span-6">
                <FwbInput
                  v-model="form.detail_address"
                  :placeholder="lang().label.detail_address"
                  :label="lang().label.detail_address"
                />
                <InputError
                  class="mt-2"
                  :message="form.errors.detail_address"
                />
              </div>

              <div class="col-span-6">
                <FwbFileInput accept="image/*" v-model="form.banner" :label="lang().label.banner" />
                <InputError class="mt-2" :message="form.errors.banner" />
              </div>

              <div class="col-span-6">
                <FwbFileInput accept="image/*" v-model="form.cover" :label="lang().label.cover" />
                <InputError class="mt-2" :message="form.errors.cover" />
              </div>

              <div class="col-span-6"></div>

              <div class="col-span-6">
                <button @click="data.createOpen = true"
                    class="btn-primary mb-2" type="button">
                    {{ lang().label.add_ticket }}
                </button>                
              </div>

              <div class="col-span-6"></div>

              <div class="col-span-12">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">
                  {{ lang().label.list_ticket }}
                </h3>
                <fwb-table>
                  <fwb-table-head>
                    <fwb-table-head-cell>Name</fwb-table-head-cell>
                    <fwb-table-head-cell>Date</fwb-table-head-cell>
                    <fwb-table-head-cell>Time</fwb-table-head-cell>
                    <fwb-table-head-cell>Contact Person</fwb-table-head-cell>
                    <fwb-table-head-cell>HTM</fwb-table-head-cell>
                    <fwb-table-head-cell>Refunable</fwb-table-head-cell>
                    <fwb-table-head-cell>
                      <span class="sr-only">Edit</span>
                      <span class="sr-only">Delete</span>
                    </fwb-table-head-cell>
                  </fwb-table-head>
                  <fwb-table-body>
                    <fwb-table-row v-for="(detail, index) in form.details"
                      :key="index">
                      <fwb-table-cell>{{ detail.name }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.date }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.time_start }} - {{ detail.time_end }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.contact }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.price == 0 ? 'Free' : detail.price }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.is_refundable == true ? 'Yes' : 'No' }}</fwb-table-cell>
                      <fwb-table-cell>
                        <fwb-button type="button" size="xs" class="mr-2" color="default">Edit</fwb-button>
                        <fwb-button type="button" size="xs" @click="removeTicket(index)" color="red">Delete</fwb-button>
                      </fwb-table-cell>
                    </fwb-table-row>
                  </fwb-table-body>
                </fwb-table>
              </div>

              <div class="flex justify-start gap-2 col-span-6 sm:col-full">
                <PrimaryButton
                  type="submit"
                  :class="{
                    'opacity-25': form.processing || form.details.length == 0,
                  }"
                  :disabled="form.processing || form.details.length == 0"
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
  </AuthenticatedLayout>
</template>

