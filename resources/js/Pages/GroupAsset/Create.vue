<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect, onMounted, reactive } from "vue";
import { FwbSelect, FwbInput } from "flowbite-vue";
import Multiselect from "@vueform/multiselect";

const props = defineProps({
  show: Boolean,
  title: String,
  metodePenyusutan: Object,
  coa: Object,
  coaAkumulasi: Object,
  coaDepresiasi: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
  name: "",
  coa_id: "",
  coa_akumulasi_id: "",
  coa_depresiasi_id: "",
  metode_penyusutan: "",
  umur: "",
});

const create = () => {
  form.post(route("group-asset.store"), {
    preserveScroll: true,
    onSuccess: () => {
      emit("close");
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};

onMounted(() => {});

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
              v-model="form.name"
              :placeholder="lang().placeholder.name"
              :label="lang().placeholder.name"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="coa" :value="lang().label.coa" />

            <Multiselect
              id="coa"
              v-model="form.coa_id"
              :options="coa"
              track-by="label"
              label="label"
              :searchable="true"
              placeholder="Select"
            />

            <InputError class="mt-2" :message="form.errors.coa_id" />
          </div>
          <div>
            <InputLabel for="name" :value="lang().label.coa_akumulasi" />

            <Multiselect
              v-model="form.coa_akumulasi_id"
              :options="coaAkumulasi"
              track-by="label"
              label="label"
              :searchable="true"
              placeholder="Select"
            />

            <InputError class="mt-2" :message="form.errors.coa_akumulasi_id" />
          </div>
          <div>
            <InputLabel for="name" :value="lang().label.coa_depresiasi" />

            <Multiselect
              v-model="form.coa_depresiasi_id"
              :options="coaDepresiasi"
              track-by="label"
              label="label"
              :searchable="true"
              placeholder="Select"
            />

            <InputError class="mt-2" :message="form.errors.coa_depresiasi_id" />
          </div>
          <div>
            <FwbInput type="number"
              v-model="form.umur"
              :placeholder="lang().label.umur"
              :label="lang().label.umur"
            />
            <InputError class="mt-2" :message="form.errors.umur" />
          </div>
          <div>
            <InputLabel for="name" :value="lang().label.metode_penyusutan" />

            <Multiselect
              v-model="form.metode_penyusutan"
              :options="metodePenyusutan"
              track-by="value"
              label="label"
              :searchable="true"
              placeholder="Select"
            />

            <InputError class="mt-2" :message="form.errors.metode_penyusutan" />
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

<style src="@vueform/multiselect/themes/default.css"></style>