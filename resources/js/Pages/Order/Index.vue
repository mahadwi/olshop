<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed, onMounted, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { priceFormat } from '../../helper.js'
import pkg from "lodash";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import Order from "@/Pages/Order/Order.vue";
import { store } from "@/Pages/Order/store.js";

import {
  FwbTextarea, FwbFileInput, FwbInput,
  FwbA,
  FwbTab,
  FwbTabs,
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

const { _, debounce, pickBy } = pkg;

const props = defineProps({
  title: String,
  breadcrumbs: Object,
  orders: Object,
	perPage: Number,
});

const orderState = [
    'All', 'Unpaid', 'On Process', 'On Going', 'Completed', 'Cancel', 'Offline'
];

const dataSet =  usePage().props.app.perpage;

const updateStore = () => {
	store.orders = props.orders.data;
}

onMounted(() => {
		updateStore();   
});

watch(
    () => ({
        params: _.cloneDeep(store.params),
    }),
    debounce(async () => {
        let params = pickBy(store.params);

				await new Promise((resolve, reject) => {
						router.get(route("order.index"), params, {
								replace: true,
								preserveState: true,
								preserveScroll: true,
								onSuccess: resolve, // Resolve the Promise on success
								onError: reject // Reject the Promise on error (optional)
						});
				});

				updateStore();
        
    }, 150)
);

watch(() => props.orders, (newOrder) => {
	store.orders = newOrder.data;
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
          
          <fwb-tabs v-model="store.params.state" variant="pills" class="mt-5">
						<fwb-tab v-for="(state, index) in orderState"
               :key="index" :name="state" :title="state">
							<Order :dataSet="dataSet"
								
								/>
						</fwb-tab>						
          </fwb-tabs>          
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

