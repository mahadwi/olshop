<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput, FwbSelect } from "flowbite-vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect, ref, onMounted, reactive } from "vue";
import DatePicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";
import { priceFormat } from "../../helper";

import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const props = defineProps({
  show: Boolean,
  title: String,
  booking: Object,
});

const emit = defineEmits(["close"]);

const data = reactive({
  name: "",
  total:""
});

const form = useForm({
    email: "",    
    message: "",    
});


const create = () => {
    form.post(route("send-email"), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

watchEffect(() => {
    if (props.show) {
				form.email = props.booking.user.email;
				data.total = priceFormat(props.booking.total)				
    }
});

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});
</script>

<template>
  <section class="space-y-6">
    <Modal :show="props.show" @close="emit('close')">
      <form class="p-6" @submit.prevent="create">
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
          {{ lang().label.detail_booking }}
        </h2>
        <div class="my-6 space-y-4">
          <div>
            <FwbInput
              v-model="props.booking.user.name" readonly
              :placeholder="lang().placeholder.name"
              :label="lang().placeholder.name"
            />
          </div>        
					<div>
            <FwbInput
              v-model="props.booking.user.email" readonly
              :placeholder="lang().label.email"
              :label="lang().label.email"
            />
          </div>       
					<div>
            <FwbInput
              v-model="props.booking.user.phone" readonly
              :placeholder="lang().label.no_hp"
              :label="lang().label.no_hp"
            />
          </div>       
					<div>
            <FwbInput
              v-model="props.booking.code" readonly
              :placeholder="lang().label.code"
              :label="lang().label.code"
            />
          </div>
					<div>
            <FwbInput
              v-model="props.booking.booking_time" readonly
              :placeholder="lang().label.booking_time"
              :label="lang().label.booking_time"
            />
          </div> 
					<div>
            <FwbInput
              v-model="props.booking.qty" readonly
              :placeholder="lang().label.qty"
              :label="lang().label.qty"
            />
          </div>       
					<div>
            <FwbInput
              v-model="props.booking.voucher" readonly
              :placeholder="lang().label.voucher"
              :label="lang().label.voucher"
            />
          </div>       
					<div>
            <FwbInput
              v-model="data.total" readonly
              :placeholder="lang().label.total"
              :label="lang().label.total"
            />
          </div>     
					<div>
            <FwbInput
              v-model="props.booking.paymentable.status" readonly
              :placeholder="lang().label.payment_status"
              :label="lang().label.payment_status"
            />
          </div>      
					<div>
            <FwbInput
              v-model="props.booking.note" readonly
              :placeholder="lang().label.note"
              :label="lang().label.note"
            />
          </div>   
					<div>
            <FwbInput readonly
              v-model="props.booking.message"
              :placeholder="lang().label.message"
              :label="lang().label.message"
            />
          </div>    
					<div>
						<label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{ lang().label.reply_message }} </label>
						<QuillEditor theme="snow" toolbar="full" content-type="html" :placeholder="lang().label.reply_message" v-model:content="form.message" />
						<InputError class="mt-2" :message="form.errors.message" />
          </div>      					      
        </div>
        <div class="flex justify-end">
          <SecondaryButton @click="emit('close')">
            {{ lang().button.close }}
          </SecondaryButton>
          <PrimaryButton type="submit" class="ml-3">
            {{ lang().label.reply_message }}
          </PrimaryButton>
        </div>
      </form>
    </Modal>
  </section>
</template>
