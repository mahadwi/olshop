<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { usePage } from "@inertiajs/vue3";
import moment from 'moment';

import { 
	FwbButton, FwbModal, FwbInput, FwbAlert, 
	FwbTimeline,
  FwbTimelineBody,
  FwbTimelineContent,
  FwbTimelineItem,
  FwbTimelinePoint,
  FwbTimelineTime,
  FwbTimelineTitle } from 'flowbite-vue'


import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, router } from "@inertiajs/vue3";
import { onMounted, computed, reactive } from "vue";
import { store } from "@/Pages/Order/store.js";
import { priceFormat } from "../../helper";
import OrderDetail from "@/Pages/Order/OrderDetail.vue";
import { ref } from 'vue';
import pkg from "lodash";
const { _, debounce, pickBy } = pkg;

const props = defineProps({
    show: Boolean,
    title: String,
    search: Object,
		dataSet:Array,
});

const dataOrder = [
	{
		label:'Latest',
		value:'desc'
	},
	{
		label:'Oldest',
		value:'asc'
	},
]

const formCancel = useForm({
  status: "",  
});

const formResi = useForm({
	resi:"",
  status: "",  
	resi_date:moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
});

const tmpOrder = ref('');
const modalCancel = ref(false)
const modalInputResi = ref(false)
const modalCekResi = ref(false)
const dataResi = ref('');

function showModalWrapper(type) {
	// modalType.value = type;
	if (type === 'cancel') {
		showModal(modalCancel);
	} else if (type === 'inputResi') {
		formResi.resi = '';
		showModal(modalInputResi);
	} else if (type === 'cekResi') {
		cekResi();
	}
}

function closeModalWrapper(type) {
	if (type === 'cancel') {
		closeModal(modalCancel);
	} else if (type === 'inputResi') {
		closeModal(modalInputResi);
	} else if (type === 'cekResi') {
		closeModal(modalCekResi);
	}
}

function closeModal (modal) {
  modal.value = false
}
function showModal (modal) {
  modal.value = true
}

const cancelOrder = async () => {
	closeModal(modalCancel);

	formCancel.status = 'Cancel';
	
	await new Promise((resolve, reject) => {
		formCancel.put(route("order.update", tmpOrder.value?.id), {
			preserveScroll: true,
			onSuccess: resolve, // Resolve the Promise on success
			onError: reject // Reject the Promise on error (optional)
		});
	});
		
}

const inputResi = async () => {

	formResi.status = 'On Going';
	
	await new Promise((resolve, reject) => {
		formResi.put(route("order.update", tmpOrder.value?.id), {
			preserveScroll: true,
			onSuccess: resolve, // Resolve the Promise on success
			onError: reject // Reject the Promise on error (optional)
		});
	});

	closeModal(modalInputResi);
		
}

const cekResi = async() => {
	
	const params = {
		resi:tmpOrder.value.resi,
		courier:tmpOrder.value.courier
	};

	const response = await axios.post('/cek-resi', params);

	dataResi.value = response.data;
	tmpOrder.value = '';

	showModal(modalCekResi);
	
}

</script>

<template>

		<div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700 mb-4">
				<SelectInput
						v-model="store.params.order"
						:dataSet="dataOrder"
				/>
				<div class="flex items-center mb-4 sm:mb-0">
						<form class="sm:pr-3" action="#" method="GET">
								<label for="products-search" class="sr-only">{{ lang().placeholder.search }}</label>
								<div class="relative w-48 mt-1 sm:w-64 xl:w-96">
										<TextInput
												type="text"
												v-model="store.params.search"
												:placeholder="lang().placeholder.search"
										/>
								</div>
						</form>
				</div>
		</div>

		<div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700 mb-4">
				<SelectInput
						v-model="store.params.perPage"
						:dataSet="props.dataSet"
				/>
		</div>

    <div v-if="store.orders.length > 0" v-for="(order, index) in store.orders" :key=index class="bg-white border rounded-md shadow-lg mb-5">
				<div class="flex bg-gray-300">
					<div class="p-3">Order Status: {{ order.status }} / {{ order.code }} / {{ order.user.name }} / {{ order.orderDate }}</div>
				</div>
				
				<order-detail :order-detail="order.order_detail" :order="order"></order-detail>

				<div class="flex justify-end mb-3 mt-[-20px]">
					<div class="text-center text-md font-bold basis-1/4">Total Payment : {{ priceFormat(order.total) }}</div>
				</div>

				<hr class="w-[98%] mx-auto border-1">
				<div class="flex p-3 gap-3">
					<div v-if="order.status == 'On Going' ">
						<fwb-button :class="{ 'opacity-25': tmpOrder != '' && tmpOrder.id == order.id }" :disabled="tmpOrder != '' && tmpOrder.id == order.id" size="sm" @click="tmpOrder=order;showModalWrapper('cekResi');" color="yellow">{{ tmpOrder != '' && tmpOrder.id == order.id ? 'Loading ....' : 'Track'}}</fwb-button>
					</div>
					<div class="mt-0.5" v-if="order.status == 'On Going' ">
						<fwb-button class="align-middle" :href="`/order/${order.id}/print-label`" target="_blank" size="sm" color="green">Print Label</fwb-button>
					</div>
					<div v-if="order.status == 'On Process' && !order.is_offline">
						<fwb-button  size="sm" @click="showModalWrapper('inputResi');tmpOrder=order" color="blue">Resi</fwb-button>
					</div>					
					<div v-if="order.status == 'Unpaid' || order.status == 'On Process'">
						<fwb-button @click="showModalWrapper('cancel');tmpOrder=order" size="sm" color="red">Cancel</fwb-button>
					</div>
				</div>
		</div>

		<p v-else>Empty Data</p>

		<fwb-modal v-if="modalCancel" @close="closeModalWrapper('cancel')">
			<template #header>
				<div class="flex items-center text-lg">
					Cancel Order
				</div>
			</template>
			<template #body>
				<p class="text-base">
					Yakin Cancel Order ? 
				</p>
			</template>
			<template #footer>
				<div class="flex gap-3 justify-end">
					<fwb-button @click="cancelOrder" color="red">
						Yes
					</fwb-button>
					<fwb-button @click="closeModalWrapper('cancel')" color="alternative">
						Close
					</fwb-button>
				</div>
			</template>
		</fwb-modal>

		<fwb-modal v-if="modalInputResi" @close="closeModalWrapper('inputResi')">
			<template #header>
				<div class="flex items-center text-lg">
					Input Resi
				</div>
			</template>
			<template #body>				
				<form @submit.prevent="inputResi">
					<div class="grid grid-cols-12 gap-6">
						<div class="col-span-12">                
								<FwbInput v-model="formResi.resi" placeholder="Resi" label="Resi" />
								<InputError class="mt-2" :message="formResi.errors.resi" />                                
						</div>						
						<div class="flex justify-end gap-2 col-span-12 sm:col-full">
							<PrimaryButton
								type="submit"
								:class="{ 'opacity-25': formResi.processing || formResi.resi == ''}"
								:disabled="formResi.processing || formResi.resi == ''"
							>
								{{
									formResi.processing
										? lang().button.save + "..."
										: lang().button.save
								}}
							</PrimaryButton>
							<SecondaryButton
								:disabled="formResi.processing"
								@click="closeModalWrapper('inputResi')"
							>
								Close
							</SecondaryButton>
						</div>
					</div>
				</form>
			</template>
			<!-- <template #footer>
				<div class="flex gap-3 justify-end">
					<fwb-button @click="closeModalWrapper('inputResi')" color="alternative">
						Close
					</fwb-button>
					<fwb-button @click="cancelOrder" color="blue">
						Save
					</fwb-button>
				</div>
			</template> -->
			
		</fwb-modal>

		<fwb-modal size="3xl" v-if="modalCekResi" @close="closeModalWrapper('cekResi')">
			<template #header>
				<div class="flex items-center text-lg">
					Tracking
				</div>
			</template>
			<template #body>				
				<fwb-alert v-if="dataResi.status != 200" type="danger">
					{{ dataResi.message }}
				</fwb-alert>
				<div v-else>
					<table class="w-full p-3 mb-5 text-sm">
						<tr>
							<td class="font-semibold">Courier</td>
							<td>{{ dataResi.data.summary.courier }}</td>
							<td class="font-semibold">Resi</td>
							<td>{{ dataResi.data.summary.awb }}</td>
						</tr>
						<tr>
							<td class="font-semibold">Shipper</td>
							<td>{{ dataResi.data.detail.shipper }}</td>
							<td class="font-semibold">Receiver</td>
							<td>{{ dataResi.data.detail.receiver  }}</td>
						</tr>
						<tr>						
							<td class="font-semibold w-1/12">Origin</td>
							<td class="w-1/4">{{ dataResi.data.detail.origin }}</td>
							<td class="font-semibold w-1/12">Destination</td>
							<td class="w-1/4">{{ dataResi.data.detail.destination }}</td>
						</tr>
						<tr>						
							<td class="font-semibold">Weight</td>
							<td>{{ dataResi.data.summary.weight }}</td>
							<td class="font-semibold">Status</td>
							<td class="font-semibold">{{ dataResi.data.summary.status }}</td>
						</tr>
					</table>
					<fwb-timeline>
						<fwb-timeline-item v-for="(resi, index) in dataResi.data.history" :key="index" class="mb-5">
							<fwb-timeline-point />
							<fwb-timeline-content>
								<fwb-timeline-time class="text-gray-500">
									{{ resi.date }}
								</fwb-timeline-time>
								<fwb-timeline-body class="text-sm text-black">
									{{ resi.desc }}
								</fwb-timeline-body>						
							</fwb-timeline-content>
						</fwb-timeline-item>
					</fwb-timeline>
				</div>
			</template>
			<template #footer>
				<div class="flex gap-3 justify-end">
					<fwb-button @click="closeModalWrapper('cekResi')" color="alternative">
						Close
					</fwb-button>				
				</div>
			</template>
		</fwb-modal>
</template>
