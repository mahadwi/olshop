<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbSelect, FwbInput, FwbTextarea, FwbFileInput } from "flowbite-vue";
import SelectInput from "@/Components/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import DatePicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";

const props = defineProps({
  show: Boolean,
  title: String,
  type: Object,
  capacity: Object,
  useFor: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
  code: "",
  name: "",
  capacity:'',  
  quota:0,  
  use_for:'',  
  type:'',  
  disc_percent:0,
  disc_price:0,
  min_price:0,
  disc_price_usd:0,
  date:'',
  time:''
});

const create = () => {
  form.post(route("voucher.store"), {
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
    form.errors = {};
  }
});

</script>

<template>
  <section class="space-y-6">
    <Modal :show="props.show" @close="emit('close')">
      <form class="p-6" @submit.prevent="create">
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
          {{ lang().label.add }} {{ props.title }}
        </h2>
        <div class="my-6 space-y-4">
          <div>
            <FwbInput
              v-model="form.code"
              :placeholder="lang().label.code"
              :label="lang().label.code"
            />
            <InputError class="mt-2" :message="form.errors.code" />
          </div>
          <div>
            <FwbInput
              v-model="form.name"
              :placeholder="lang().label.name"
              :label="lang().label.name"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="date" :value="lang().label.date" />
            <date-picker
              v-model:value="form.date"
              range=true
              format="DD-MM-YYYY"
              value-type="format"
              placeholder="Select Date"
            ></date-picker>
            <InputError class="mt-2" :message="form.errors.date" />
          </div>
          <div>
            <InputLabel for="time" :value="lang().label.time" />
            <date-picker
              v-model:value="form.time"
              range="true"
              format="HH:mm"
              value-type="format"
              type="time"
              placeholder="Select time"
            ></date-picker>
            <InputError class="mt-2" :message="form.errors.time" />
          </div>
          <div>          
            <FwbSelect
              v-model="form.capacity"
              :options="props.capacity"
              :label="lang().label.capacity"
            />

            <InputError class="mt-2" :message="form.errors.capacity" />
          </div>         
          <div>
            <FwbInput :disabled="form.capacity != 'Limited' "
              v-model="form.quota"
              :placeholder="lang().label.quota"
              :label="lang().label.quota"
            />
            <InputError class="mt-2" :message="form.errors.quota" />
          </div>
          <div>          
            <FwbSelect
              v-model="form.use_for"
              :options="props.useFor"
              :label="lang().label.use_for"
            />

            <InputError class="mt-2" :message="form.errors.use_for" />
          </div> 
          <div>          
            <FwbSelect
              v-model="form.type"
              :options="props.type"
              :label="lang().label.type"
            />

            <InputError class="mt-2" :message="form.errors.type" />
          </div> 
          <div>
            <FwbInput
              :disabled="form.type != 'Percent'"
              v-model="form.disc_percent"
              :placeholder="lang().label.disc_percent"
              :label="lang().label.disc_percent"
            />
            <InputError class="mt-2" :message="form.errors.disc_percent" />
          </div>
          <div>
            <FwbInput
              :disabled="form.type != 'Price'"
              v-model="form.disc_price"
              :placeholder="lang().label.disc_price"
              :label="lang().label.disc_price"
            />
            <InputError class="mt-2" :message="form.errors.disc_price" />
          </div>
          <div>
            <FwbInput
              :disabled="form.type != 'Price'"
              v-model="form.disc_price_usd"
              :placeholder="lang().label.disc_price_usd"
              :label="lang().label.disc_price_usd"
            />
            <InputError class="mt-2" :message="form.errors.disc_price_usd" />
          </div>
          <div>
            <FwbInput
              :disabled="form.type != 'B1G1'"
              v-model="form.min_price"
              :placeholder="lang().label.min_price"
              :label="lang().label.min_price"
            />
            <InputError class="mt-2" :message="form.errors.min_price" />
          </div>
          
        </div>
        <div class="flex justify-end">
          <SecondaryButton :disabled="form.processing" @click="emit('close')">
            {{ lang().button.close }}
          </SecondaryButton>
          <PrimaryButton
            type="submit"
            class="ml-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            {{
              form.processing ? lang().button.add + "..." : lang().button.add
            }}
          </PrimaryButton>
        </div>
      </form>
    </Modal>
  </section>
</template>
