<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { watchEffect } from "vue";
import { Input } from "flowbite-vue";
import Multiselect from "@vueform/multiselect";

const bahasa = usePage().props.language.original;

const props = defineProps({
  show: Boolean,
  title: String,
  groupAsset: Object,
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

const update = () => {
  form.put(route("group-asset.update", props.groupAsset?.id), {
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
    form.name = props.groupAsset?.name;
    form.coa_id = props.groupAsset?.coa_id;
    form.coa_akumulasi_id = props.groupAsset?.coa_akumulasi_id;
    form.coa_depresiasi_id = props.groupAsset?.coa_depresiasi_id;
    form.umur = props.groupAsset?.umur;
    form.metode_penyusutan = props.groupAsset?.metode_penyusutan;
    form.errors = {};
  }
});
</script>

<template>
  <section class="space-y-6">
    <Modal :show="props.show" @close="emit('close')">
      <form class="p-6" @submit.prevent="update">
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
          {{ lang().label.edit }} {{ props.title }}
        </h2>
        <div class="my-6 space-y-4">
          <div>
            <InputLabel for="name" :value="lang().label.name" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              required
              :placeholder="lang().placeholder.name"
              :error="form.errors.name"
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
            <InputLabel for="coa_akumulasi_id" :value="lang().label.coa_akumulasi" />

            <Multiselect
              id="coa_akumulasi_id"
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
            <InputLabel for="coa_depresiasi_id" :value="lang().label.coa_depresiasi" />

            <Multiselect
							id="coa_depresiasi_id"
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
            <Input type="number"
              v-model="form.umur"
              :placeholder="lang().label.umur"
              :label="lang().label.umur"
            />
            <InputError class="mt-2" :message="form.errors.umur" />
          </div>
          <div>
            <InputLabel for="metode_penyusutan" :value="lang().label.metode_penyusutan" />

            <Multiselect
							id="metode_penyusutan"
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
            class="ml-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="update"
          >
            {{
              form.processing ? lang().button.save + "..." : lang().button.save
            }}
          </PrimaryButton>
        </div>
      </form>
    </Modal>
  </section>
</template>
