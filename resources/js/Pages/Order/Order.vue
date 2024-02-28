<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { usePage } from "@inertiajs/vue3";
import { FwbButton, FwbModal } from 'flowbite-vue'


import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, router } from "@inertiajs/vue3";
import { onMounted, computed } from "vue";
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

const form = useForm({
  status: "",  
});

const tmpOrder = ref('');
const isShowModal = ref(false)

function closeModal () {
  isShowModal.value = false
}
function showModal () {
  isShowModal.value = true
}

const cancelOrder = async () => {
	closeModal();

	form.status = 'Cancel';
	
	await new Promise((resolve, reject) => {
		form.put(route("order.update", tmpOrder.value?.id), {
			preserveScroll: true,
			onSuccess: resolve, // Resolve the Promise on success
			onError: reject // Reject the Promise on error (optional)
		});
	});
		
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
					<!-- <div>
						<p>Email Buyer</p>
					</div>
					<div>
						<p>Order Complain</p>
					</div> -->
					<div>
						<fwb-button v-if="order.status == 'Unpaid' || order.status == 'On Process'" @click="showModal();tmpOrder=order" size="sm" color="red">Cancel</fwb-button>
					</div>
				</div>
		</div>

		<p v-else>Empty Data</p>

		<fwb-modal v-if="isShowModal" @close="closeModal">
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
					<fwb-button @click="closeModal" color="alternative">
						Tutup
					</fwb-button>
					<fwb-button @click="cancelOrder" color="red">
						Yakin
					</fwb-button>
				</div>
			</template>
		</fwb-modal>
</template>
