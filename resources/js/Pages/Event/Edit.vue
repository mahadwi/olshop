<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed } from "vue";
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
import CreateTicket from "@/Pages/Event/CreateTicket.vue";
import EditTicket from "@/Pages/Event/EditTicket.vue";

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
  event: Object,
  capacity: Object,
});

const data = reactive({
    createOpen: false,
    editOpen: false,
    deleteOpen: false,
    ticket:null
});

const form = useForm({
  name: props.event.name,
  place: props.event.place,
  start_date: props.event.start_date,
  end_date: props.event.end_date,
  time_start: props.event.time_start,
  time_end: props.event.time_end,
  maps:props.event.maps,
  detail_maps:props.event.detail_maps,
  cover:"",
  banner:"",
  description: props.event.description,
  description_en: props.event.description_en,
  details: props.event.details
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
  form.post(route("event.update", props.event?.id), {
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

const canAddTicket = computed(() => {
  return form.name != '' && form.place != '' && form.start_date != ''
  && form.end_date != '' && form.time_start != '' && form.time_end != '';
});

const capacity = Object.values(props.capacity).map((data) => ({
    name: data,
    value: data,
}));

console.log(capacity);

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

    <edit-ticket
        :show="data.editOpen"
        @close="data.editOpen = false"
        :ticket="data.ticket"
        :title="props.title"
        :capacity="capacity"
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
                <FwbTextarea
                  rows="4"
                  :placeholder="lang().label.maps"
                  v-model="form.maps"
                  :label="lang().label.maps"
                />
                <InputError class="mt-2" :message="form.errors.maps" />
              </div>

              <div class="col-span-6">
                <FwbFileInput accept="image/*" v-model="form.banner" :label="lang().label.banner" />
                <InputError class="mt-2" :message="form.errors.banner" />
              </div>

              <div class="col-span-6">
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{ lang().label.detail_maps }} </label>
                  <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.detail_maps" v-model:content="form.detail_maps" />
                  <InputError class="mt-2" :message="form.errors.detail_maps" />
              </div>

              <div class="col-span-6">
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{ lang().label.description }} </label>
                  <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                  <InputError class="mt-2" :message="form.errors.description" />
              </div>

              <div class="col-span-6 mt-24">
                <FwbFileInput accept="image/*" v-model="form.cover" :label="lang().label.cover" />
                <InputError class="mt-2" :message="form.errors.cover" />
              </div>

              <div class="col-span-6 mt-24">
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{ lang().label.description_en }} </label>
                  <QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.description_en" />
                  <InputError class="mt-2" :message="form.errors.description_en" />
              </div>

              <div class="col-span-6">
                <button :disabled="!canAddTicket" @click="data.createOpen = true"
                    class="btn-primary mb-2" :class="{
                    'opacity-25': !canAddTicket,
                  }" type="button">
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
                    <fwb-table-head-cell>{{ lang().label.capacity }}</fwb-table-head-cell>
                    <fwb-table-head-cell>{{ lang().label.quota }}</fwb-table-head-cell>
                    <fwb-table-head-cell>Date</fwb-table-head-cell>
                    <fwb-table-head-cell>Time</fwb-table-head-cell>
                    <fwb-table-head-cell>Contact Person</fwb-table-head-cell>
                    <fwb-table-head-cell>HTM</fwb-table-head-cell>
                    <fwb-table-head-cell>Refundable</fwb-table-head-cell>
                    <fwb-table-head-cell>
                      <span class="sr-only">Edit</span>
                      <span class="sr-only">Delete</span>
                    </fwb-table-head-cell>
                  </fwb-table-head>
                  <fwb-table-body>
                    <fwb-table-row v-for="(detail, index) in form.details"
                      :key="index">
                      <fwb-table-cell>{{ detail.name }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.capacity }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.quota }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.date }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.time_start }} - {{ detail.time_end }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.contact }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.price == 0 ? 'Free' : priceFormat(detail.price) }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.is_refundable == true ? 'Yes' : 'No' }}</fwb-table-cell>
                      <fwb-table-cell>
                        <fwb-button type="button" size="xs" class="mr-2" @click="
                                                                (data.editOpen = true),
                                                                    (data.ticket = detail)" color="default">Edit</fwb-button>
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

