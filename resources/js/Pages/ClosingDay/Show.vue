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
  closingDay: Object,
  orders: Object,
});

// const data = reactive({
//     ongkir: priceFormat(props.order.ongkir),
//     discount: priceFormat(props.order.discount),
//     total: priceFormat(props.order.total),
// });

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
            <div class="col-span-12">
              <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <tbody>
                    <tr class="bg-white">
                      <th class="  px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ lang().label.date }}
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ props.closingDay.date }}
                      </td>
                      <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ lang().label.starting_cash }}
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ priceFormat(props.closingDay.starting_cash) }}
                      </td>
                        
                    </tr>
                    <tr class="bg-white">
                      <th class="  px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ lang().label.cash_in }}
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ priceFormat(props.closingDay.cash_in)}}
                      </td>
                      <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ lang().label.cash_out }}
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ priceFormat(props.closingDay.cash_out) }}
                      </td>
                        
                    </tr>
                    <tr class="bg-white">
                      <th class="  px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Total
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ priceFormat(props.closingDay.total_cash) }}
                      </td>
                      <th class="  px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Open
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ props.closingDay.open_formatted }}
                      </td>                                              
                    </tr>
                    <tr class="bg-white">
                      <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ lang().label.cashier }}
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ props.closingDay.kasir_open.name }}
                      </td>
                      <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Close
                      </th>
                      <td class=" px-6 py-4 text-left">
                        {{ props.closingDay.close_formatted }}
                      </td>                      
                    </tr>
                </tbody>
              </table>
            </div>
            <div class="col-span-12">
              <h3 class="mb-4 text-xl font-semibold dark:text-white">
                {{ lang().label.order }}
              </h3>
              <fwb-table>
                <fwb-table-head>
                  <fwb-table-head-cell>{{ lang().label.invoice }}</fwb-table-head-cell>
                  <fwb-table-head-cell class="text-right">{{ lang().label.cash_in }}</fwb-table-head-cell>
                  <fwb-table-head-cell class="text-right">{{ lang().label.cash_out }}</fwb-table-head-cell>
                  <fwb-table-head-cell class="text-right">{{ lang().label.total }}</fwb-table-head-cell>
                </fwb-table-head>
                <fwb-table-body>
                  <fwb-table-row v-for="(order, index) in props.orders"
                    :key="index">
                    <fwb-table-cell>{{ order.code }}</fwb-table-cell>
                    <fwb-table-cell class="text-right">{{ priceFormat(order.pay) }}</fwb-table-cell>
                    <fwb-table-cell class="text-right">{{ priceFormat(order.return) }}</fwb-table-cell>               
                    <fwb-table-cell class="text-right">{{ priceFormat(order.total) }}</fwb-table-cell>               
                  </fwb-table-row>
                  <fwb-table-row class="font-bold">
                    <fwb-table-cell>{{ lang().label.total }}</fwb-table-cell>
                    <fwb-table-cell class="text-right">{{ priceFormat(props.closingDay.cash_in) }}</fwb-table-cell>
                    <fwb-table-cell class="text-right">{{ priceFormat(props.closingDay.cash_out) }}</fwb-table-cell>               
                    <fwb-table-cell class="text-right">{{ priceFormat(props.closingDay.total_cash) }}</fwb-table-cell>  
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

