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
import DetailBooking from "@/Pages/EventDetail/DetailBooking.vue";

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
  eventDetail: Object,
});

const data = reactive({
    detailOpen: false,
    booking:null
});

const form = useForm({
  name: props.eventDetail.name,
  date: props.eventDetail.date,
  time_start: props.eventDetail.time_start,
  time_end: props.eventDetail.time_end,
  capacity: props.eventDetail.capacity,
  quota: props.eventDetail.quota,
  price: priceFormat(props.eventDetail.price),
  contact: props.eventDetail.contact,
  is_refundable: props.eventDetail.is_refundable == true ? 'Yes' : 'No',
});

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

    <detail-booking
        :show="data.detailOpen"
        @close="data.detailOpen = false"
        :booking="data.booking"
        :title="props.title"
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
                  v-model="form.name" readonly
                  :placeholder="lang().label.name"
                  :label="lang().label.name"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.date"
                  :placeholder="lang().label.date"
                  :label="lang().label.date"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.time_start"
                  :placeholder="lang().label.time_start"
                  :label="lang().label.time_start"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.time_end"
                  :placeholder="lang().label.time_end"
                  :label="lang().label.time_end"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.capacity"
                  :placeholder="lang().label.capacity"
                  :label="lang().label.capacity"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.quota"
                  :placeholder="lang().label.quota"
                  :label="lang().label.quota"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.price"
                  :placeholder="lang().label.price"
                  :label="lang().label.price"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.contact"
                  :placeholder="lang().label.contact"
                  :label="lang().label.contact"
                />
              </div>
              <div class="col-span-6">
                <FwbInput readonly
                  v-model="form.is_refundable"
                  :placeholder="lang().label.refundable"
                  :label="lang().label.refundable"
                />
              </div>

              <div class="col-span-6"></div>

              <div class="col-span-12">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">
                  {{ lang().label.booking_list }}
                </h3>
                <fwb-table>
                  <fwb-table-head>
                    <fwb-table-head-cell>{{ lang().label.name }}</fwb-table-head-cell>
                    <fwb-table-head-cell>{{ lang().label.booking_time }}</fwb-table-head-cell>
                    <fwb-table-head-cell>{{ lang().label.qty }}</fwb-table-head-cell>
                    <fwb-table-head-cell>Total</fwb-table-head-cell>
                    <fwb-table-head-cell>{{ lang().label.payment_status }}</fwb-table-head-cell>
                    <fwb-table-head-cell>
                      <span class="sr-only">Detail</span>
                    </fwb-table-head-cell>
                  </fwb-table-head>
                  <fwb-table-body>
                    <fwb-table-row v-for="(detail, index) in props.eventDetail.bookings"
                      :key="index">
                      <fwb-table-cell>{{ detail.user.name }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.booking_time }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.qty }}</fwb-table-cell>
                      <fwb-table-cell>{{ priceFormat(detail.total) }}</fwb-table-cell>
                      <fwb-table-cell>{{ detail.paymentable.status }}</fwb-table-cell>
                      <fwb-table-cell>
                        <fwb-button type="button" size="xs" class="mr-2" @click="
                        (data.detailOpen = true),
                            (data.booking = detail)" color="default">Detail
                        </fwb-button>
                      </fwb-table-cell>
                    </fwb-table-row>
                  </fwb-table-body>
                </fwb-table>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

