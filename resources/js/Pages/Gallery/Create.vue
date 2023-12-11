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
import FilePondInput from "@/Components/FilePondInput.vue";

const props = defineProps({
  show: Boolean,
  title: String,
  gallerySection: Object,
  product: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
  section: "",
  title: "",
  title_en: "",
  product_id:'',
  image: [],
});

const create = () => {
  form.post(route("gallery.store"), {
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

const gallerySection = Object.values(props.gallerySection).map((data) => ({
    name: data,
    value: data,
}));

let product = props.product?.map((data) => ({
    label: data.name,
    value: data.id,
}));

product = [{label : 'Select...', value: ''}, ...product];

</script>

<template>
  <section class="space-y-6">
    <Modal :show="props.show" @close="emit('close')">
      <form class="p-6" @submit.prevent="create">
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
          {{ lang().label.add }} {{ props.title }}
        </h2>
        <div class="my-6 space-y-4">
          <FwbSelect
            v-model="form.section"
            :options="gallerySection"
            :label="lang().label.section"
          />

          <InputError class="mt-2" :message="form.errors.section" />
        </div>
        <div class="my-6 space-y-4">
          <FwbInput
            v-model="form.title"
            :placeholder="lang().label.title"
            :label="lang().label.title"
          />
          <InputError class="mt-2" :message="form.errors.title" />
        </div>
        <div class="my-6 space-y-4">
          <FwbInput
            v-model="form.title_en"
            :placeholder="lang().label.title_en"
            :label="lang().label.title_en"
          />
          <InputError class="mt-2" :message="form.errors.title_en" />
        </div>
        <div class="my-6 space-y-4">
            <InputLabel for="product" :value="lang().label.product" />
            <SelectInput
                id="product"
                class="mt-1 block w-full"
                v-model="form.product_id"
                :dataSet="product"
            >
            </SelectInput>
            <InputError class="mt-2" :message="form.errors.product_id" />
        </div>
        <div class="my-6 space-y-4">
          <FilePondInput v-model="form.image" accept="image/*" />

          <InputError class="mt-2" :message="form.errors.image" />
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
