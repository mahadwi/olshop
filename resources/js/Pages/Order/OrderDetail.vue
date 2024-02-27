<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect, ref, computed } from "vue";
import { store } from "@/Pages/Order/store.js";
import { priceFormat } from "../../helper";
import { objectToString } from "@vue/shared";

const props = defineProps({
		order:Object,
    orderDetail: Array,
});

const showAll = ref(false);

const filterDetail = computed(() => {
  return showAll.value ? props.orderDetail : props.orderDetail.slice(0, 1);
});

const textView = computed(() => {
	return showAll.value ? 'View Less' : 'View More';
});


</script>

<template>
							
			<div v-for="(detail, index) in filterDetail" :key="index" class="px-5 py-3 flex gap-2">
					<img class="mb-2 rounded-lg w-28 h-28 sm:mb-0 xl:mb-2 2xl:mb-0" :src="`/image/product/`+detail.product.images[0].name" alt="product picture">
					<div class="basis-1/4">
						<p class="font-bold">{{ detail.product.name }}</p>
						<p class="mt-3">{{ `${detail.qty} x ${priceFormat(detail.price)}` }}</p>
					</div>
					<div v-if="index == 0" class="basis-1/4 text-center">
						<p class="font-bold">Address</p>
						<p class="mt-3">{{ order.address ? order.address.address : 'Pickup' }}</p>
					</div>
					<div v-if="index == 0" class="text-center basis-1/4">
						<p class="font-bold">Courier</p>
						<p class="mt-3">{{ priceFormat(order.ongkir) }}</p>
					</div>
					<div v-if="index == 0" >
						<p class="font-bold basis-1/4">Payment Method</p>
						<p class="text-center mt-3">{{ order.paymentable.status == 'Paid' ? order.paymentable.payment_channel : '-'}}</p>
					</div>
			</div>			

			<div class="flex">
				<a href="javascript:void(0)" v-if="order.order_detail.length > 1" @click="showAll = !showAll" class="mx-auto text-blue-700">{{ textView }}</a>
			</div>
				
</template>
