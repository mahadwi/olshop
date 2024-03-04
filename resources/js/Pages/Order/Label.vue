<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed, onMounted, onBeforeMount } from "vue";
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

const props = defineProps({
  title: String,
  order: Object,
  profile: Object,
});

onMounted(() => {
  window.print();
})

</script>

<template>
  <Head :title="props.title" />

  <div class="container w-[80%] mx-auto px-4 mt-3 border border-gray-500">
			<div class="flex justify-between items-center py-4">
					<div class="flex items-center">
							<img :src="props.profile.logo_url" alt="Logo" class="w-25 h-11 mr-4">
					</div>
			</div>
			<hr class="w-full mx-auto border-[#FFD96B] rounded">
			<div class="flex flex-col md:flex-row gap-4 mt-3">
					<div class="w-full md:w-1/2 ">
							<h3 class="text-gray-700">Nomor Pemesanan</h3>
							<h3 class="text-lg font-bold mb-2">{{ props.order.code }}</h3>
					</div>
					<div class="w-full md:w-1/2 ">
							<h3 class="text-gray-700">Nomor Resi</h3>
							<h3 class="text-3xl font-bold mb-2">{{ props.order.resi }}</h3>                
					</div>
			</div>
			<div class="flex flex-col md:flex-row gap-4 ">
					<div class="w-full md:w-1/2 ">
						<h3 class="text-gray-700">Tanggal Pemesanan</h3>
						<h3 class="text-md font-bold mb-2">{{ props.order.orderDate }}</h3>
					</div>
					<div class="w-full md:w-1/2">
						<h3 class="text-gray-700">Tanggal Pengiriman</h3>
						<h3 class="text-md font-bold mb-2">{{ props.order.resiDateFix }}</h3>
					</div>
			</div>
			<div class="flex flex-col">					
				<h3 class="text-gray-700">Kurir</h3>
				<h3 class="text-md font-bold mb-2">{{ props.order.courier.toUpperCase() }}</h3>
			</div>
			<div class="flex flex-col md:flex-row gap-4 ">
					<div class="w-full md:w-1/2 ">
						<h3 class="text-gray-700">Kepada</h3>
						<h3 class="text-md font-bold ">{{ props.order.address.name }}</h3>
						<h3 class="text-md">{{ props.order.address.handphone }}</h3>
						<h3 class="text-md ">{{ props.order.address.full_address }}</h3>
					</div>
					<div class="w-full md:w-1/2">
						<h3 class="text-gray-700">Dari</h3>
						<h3 class="text-md font-bold">{{ props.profile.name }}</h3>
						<h3 class="text-md">{{ props.profile.telp }}</h3>
						<h3 class="text-md">{{ props.profile.full_address }}</h3>
					</div>
			</div>
			<table class="w-full text-sm text-left text-gray-700 mt-5">
        <thead class="text-xs border-dashed border-y-2 border-y-gray-300 text-gray-700 uppercase ">
            <tr>
                <th scope="col" class="px-6 py-3">
                    No
                </th>
                <th scope="col" class="px-6 py-3">
                    Product
                </th>
                <th scope="col" class="px-6 py-3">
                    Jumlah
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(detail, index) in props.order.order_detail" :key="index">
                <th scope="row" class="px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ index + 1 }}
                </th>
                <td class="px-6 py-2">
									{{ detail.product.name }}
                </td>
                <td class="px-6 py-2">
                  {{ detail.qty }}
                </td>
            </tr>
            
        </tbody>        
    </table>				
	</div>
</template>

