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
  order: Object,
});

const data = reactive({
    ongkir: priceFormat(props.order.ongkir),
    discount: priceFormat(props.order.discount),
    total: priceFormat(props.order.total),
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
          <div class="grid grid-cols-12 gap-6">
            <div class="col-span-6">
              <FwbInput readonly
                v-model="props.order.code"
                :placeholder="lang().label.code"
                :label="lang().label.code"
              />
            </div>
            <div class="col-span-6">
              <FwbInput
                v-model="props.order.created_at" readonly
                :placeholder="lang().label.date"
                :label="lang().label.date"
              />
            </div>      
            <div class="col-span-6">
              <FwbInput
                v-model="props.order.status" readonly
                :placeholder="lang().label.status"
                :label="lang().label.status"
              />
            </div>     
            <div class="col-span-6">
              <FwbInput
                v-model="data.ongkir" readonly
                :placeholder="lang().label.ongkir"
                :label="lang().label.ongkir"
              />
            </div>      
            <div class="col-span-6">
              <FwbInput
                v-model="props.order.voucher" readonly
                :placeholder="lang().label.voucher"
                :label="lang().label.voucher"
              />
            </div>   
            <div class="col-span-6">
              <FwbInput
                v-model="data.discount" readonly
                :placeholder="lang().label.discount"
                :label="lang().label.discount"
              />
            </div>                 
            <div class="col-span-6">
              <FwbInput
                v-model="data.total" readonly
                :placeholder="lang().label.total"
                :label="lang().label.total"
              />
            </div>    
            <div class="col-span-6">
              <FwbInput
                v-model="props.order.paymentable.status" readonly
                :placeholder="lang().label.payment_status"
                :label="lang().label.payment_status"
              />
            </div>     
            <div class="col-span-6">
              <FwbInput
                v-model="props.order.note" readonly
                :placeholder="lang().label.note"
                :label="lang().label.note"
              />
            </div>    
            
            <div class="col-span-12">
              <h3 class="mb-4 text-xl font-semibold dark:text-white">
                {{ lang().label.product }}
              </h3>
              <fwb-table>
                <fwb-table-head>
                  <fwb-table-head-cell>{{ lang().label.name }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.qty }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.price }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.total }}</fwb-table-head-cell>
                  <fwb-table-head-cell>
                    <span class="sr-only">Detail</span>
                    <!-- <span class="sr-only">Delete</span> -->
                  </fwb-table-head-cell>
                </fwb-table-head>
                <fwb-table-body>
                  <fwb-table-row v-for="(detail, index) in props.order.order_detail"
                    :key="index">
                    <fwb-table-cell>{{ detail.product.name }}</fwb-table-cell>
                    <fwb-table-cell>{{ detail.qty }}</fwb-table-cell>
                    <fwb-table-cell>{{ priceFormat(detail.price) }}</fwb-table-cell>               
                    <fwb-table-cell>{{ priceFormat(detail.total) }}</fwb-table-cell>               
                    <fwb-table-cell>
                      <!-- <Link v-if="detail.bookings.length > 0"
                          :href="route('event-detail.show', detail.id)"
                          class="btn-primary text-xs"
                        >
                          {{ lang().label.booking_list }}
                      </Link> -->
                    </fwb-table-cell>
                  </fwb-table-row>
                </fwb-table-body>
              </fwb-table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

