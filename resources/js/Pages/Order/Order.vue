<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { store } from "@/Pages/Order/store.js";
import { priceFormat } from "../../helper";
import OrderDetail from "@/Pages/Order/OrderDetail.vue";

const props = defineProps({
    show: Boolean,
    title: String,
    orders: Object,
    search: Object,
});

</script>

<template>
    <div v-if="store.orders.length > 0" v-for="(order, index) in store.orders" :key=index class="bg-white border rounded-md shadow-lg mb-5">
				<div class="flex bg-gray-300">
					<div class="p-3">Order Status: {{ order.status }} / {{ order.code }} / {{ order.user.name }} / {{ order.orderDate }}</div>
				</div>
				
				<order-detail :order-detail="order.order_detail" :order="order"></order-detail>

				<div class="flex justify-end mb-3 mt-[-20px]">
					<div class="text-center text-md font-bold basis-1/4">Total Payment : {{ priceFormat(order.total) }}</div>
				</div>

				<hr class="w-[96%] mx-auto border-1">
				<!-- <div class="flex p-3 gap-3">
					<div>
						<p>Email Buyer</p>
					</div>
					<div>
						<p>Order Complain</p>
					</div>
					<div>
						<p>Konfirmasi Pembayaran</p>
					</div>
				</div> -->
		</div>

		<p v-else>Empty Data</p>
</template>
