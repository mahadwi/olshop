<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput, FwbSelect } from "flowbite-vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect, ref, onMounted, reactive } from "vue";

const props = defineProps({
  show: Boolean,
  title: String,
  agreement: Object,
  capacity: Object,
});

const emit = defineEmits(["close"]);

const data = reactive({
  note:'',
  is_approved:''
});

const status = [
    {
        name: "Approve",
        value: true
    },
    {
        name: "Not Approve",
        value: false
    }    
];


const update = () => {
  props.agreement.note = data.note;
  props.agreement.is_approved = data.is_approved;

  emit("close");
};

watchEffect(() => {
    if (props.show) {
				data.note = props.agreement.note;
        data.is_approved = props.agreement.is_approved;
    }
});

</script>

<template>
  <section class="space-y-6">
    <Modal :show="props.show" @close="emit('close')">
      <form class="p-6" @submit.prevent="update">
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
          {{ lang().label.edit }} {{ lang().label.agreement }}
        </h2>
        <div class="my-6 space-y-4">
					<div>
							<FwbSelect
									v-model="data.is_approved"
									:options="status"
									:label="lang().label.status"
							/>
					</div>
          <div>
            <FwbTextarea rows="4" :placeholder="lang().label.note" v-model="data.note" :label="lang().label.note" />
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
