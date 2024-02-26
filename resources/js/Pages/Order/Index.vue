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


const props = defineProps({
  title: String,
  breadcrumbs: Object,
  orders: Object,
});

const activeTab = ref("All");

const form = useForm({
  duration: "",
  operationals:[],
});

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const create = () => {
  form.post(route("operational.store"), {
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
          
          <fwb-tabs v-model="activeTab" variant="pills" class="mt-5">
						<fwb-tab name="All" title="All">
							<div class="bg-white border rounded-md shadow-lg mb-5">
									<div class="flex bg-gray-300">
										<div class="p-3">Order Status: Completed / INV123334 / Slamet / 22-09-2020 13:20</div>
									</div>
									
									<div class="px-5 py-3 flex gap-2">
											<img class="mb-2 rounded-lg w-28 h-28 sm:mb-0 xl:mb-2 2xl:mb-0" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="Jese picture">
											<div class="basis-1/4">
												<p class="font-bold">Prada Re-Edition 2005 Re-Nylon  mini bag</p>
												<p class="mt-3">1 x Rp. 15.000.000</p>
											</div>
											<div class="basis-1/4 text-center">
												<p class="font-bold">Address</p>
												<p class="mt-3">Jl. Lidah Bukit Mas, Lidah Wetan, Kec. Lakarsantri, Surabaya, Jawa Timur 60213</p>
											</div>
											<div class="text-center basis-1/4">
												<p class="font-bold">Courier</p>
												<p class="mt-3">Rp. 1.000.000</p>
											</div>
											<div>
												<p class="font-bold basis-1/4">Payment Method</p>
												<p class="text-center mt-3">BCA VA</p>
											</div>
									</div>
<!-- 
									<div class="px-5 py-3 flex gap-2">
											<img class="mb-2 rounded-lg w-28 h-28 sm:mb-0 xl:mb-2 2xl:mb-0" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="Jese picture">
											<div class="basis-1/4">
												<p class="font-bold">Prada Re-Edition 2005 Re-Nylon  mini bag</p>
												<p class="mt-3">1 x Rp. 15.000.000</p>
											</div>
											
									</div> -->

									<div class="flex justify-end mb-3 mt-[-20px]">
										<div class="text-center text-md font-bold basis-1/4">Total Payment : Rp. 16.000.000</div>
									</div>

									<hr class="w-[96%] mx-auto border-1">
									<div class="flex p-3 gap-3">
										<div>
											<p>Email Buyer</p>
										</div>
										<div>
											<p>Order Complain</p>
										</div>
										<div>
											<p>Konfirmasi Pembayaran</p>
										</div>
									</div>
							</div>
						</fwb-tab>
						<fwb-tab name="Unpaid" title="Unpaid">

						</fwb-tab>
						<fwb-tab name="On Process" title="On Process">

						</fwb-tab>
						<fwb-tab name="On Going" title="On Going">

						</fwb-tab>
						<fwb-tab name="Completed" title="Completed">

						</fwb-tab>
						<fwb-tab name="Return" title="Return">

						</fwb-tab>
						<fwb-tab name="Offline" title="Offline">
						
						</fwb-tab>
          </fwb-tabs>          
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

