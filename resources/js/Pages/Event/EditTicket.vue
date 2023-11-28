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

const props = defineProps({
  show: Boolean,
  title: String,
  ticket: Object,
});

const emit = defineEmits(["close"]);

const data = reactive({
  name: "",
  date: "",
  time_start: "",
  time_end: "",
  contact: "",
  price: 0,
  is_refundable: "Yes",
});


const refundable = [
    {
        name: "Yes",
        value: true
    },
    {
        name: "No",
        value: false
    }    
];

const update = () => {
  props.ticket.name = data.name;
  props.ticket.date = data.date;
  props.ticket.time_start = data.time_start;
  props.ticket.time_end = data.time_end;
  props.ticket.contact = data.contact;
  props.ticket.price = data.price;
  props.ticket.is_refundable = data.is_refundable;

  emit("close");
};

watchEffect(() => {
    if (props.show) {
				data.name = props.ticket.name;
        data.time_start = props.ticket.time_start;
        data.time_end = props.ticket.time_end;
				data.date = props.ticket.date;
        data.contact = props.ticket.contact;
        data.price = props.ticket.price;;
        data.is_refundable = props.ticket.is_refundable;
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
      <form class="p-6" @submit.prevent="update">
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
          {{ lang().label.edit }} {{ lang().label.ticket }}
        </h2>
        <div class="my-6 space-y-4">
          <div>
            <FwbInput
              v-model="data.name"
              :placeholder="lang().placeholder.name"
              :label="lang().placeholder.name"
            />
          </div>
					<div>
            <InputLabel for="date" :value="lang().label.date" />
						<date-picker  v-model:value="data.date" value-type="format" format="DD-MM-YYYY"></date-picker>
          </div>
          
          <div>
            <InputLabel for="time" :value="lang().label.time_start" />
            <date-picker
              v-model:value="data.time_start"
              format="hh:mm A"
              value-type="format"
              type="time"
              placeholder="Select time"
            ></date-picker>
          </div>
					<div>
            <InputLabel for="time" :value="lang().label.time_end" />
            <date-picker
              v-model:value="data.time_end"
              format="hh:mm A"
              value-type="format"
              type="time"
              placeholder="Select time"
            ></date-picker>
          </div>
					<div>
            <FwbInput
              v-model="data.contact"
              :placeholder="lang().label.contact"
              :label="lang().label.contact"
            />
          </div>
					<div>
            <FwbInput
              v-model="data.price"
              :placeholder="lang().label.price"
              :label="lang().label.price"
            />
          </div>
					<div>
							<FwbSelect
									v-model="data.is_refundable"
									:options="refundable"
									:label="lang().label.refundable"
							/>
					</div>
        </div>
        <div class="flex justify-end">
          <SecondaryButton @click="emit('close')">
            {{ lang().button.close }}
          </SecondaryButton>
          <PrimaryButton type="submit" class="ml-3">
            {{ lang().button.save }}
          </PrimaryButton>
        </div>
      </form>
    </Modal>
  </section>
</template>
