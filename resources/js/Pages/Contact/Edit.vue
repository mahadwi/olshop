<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from "flowbite-vue";
import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
  show: Boolean,
  title: String,
  contact: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
  telp: "",
  maps: "",
  address: "",
  address_en: "",
  link: "",
  email: "",
  facebook: "",
  instagram: "",
  tiktok: "",
});

const update = () => {
  form.put(route("contact.update", props.contact?.id), {
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
    form.telp = props.contact?.telp;
    form.maps = props.contact?.maps;
    form.address = props.contact?.address;
    form.address_en = props.contact?.address_en;
    form.link = props.contact?.link;
    form.email = props.contact?.email;
    form.facebook = props.contact?.facebook;
    form.instagram = props.contact?.instagram;
    form.tiktok = props.contact?.tiktok;
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
            <InputLabel for="telp" :value="lang().label.telp" />
            <TextInput
              id="telp"
              type="text"
              class="mt-1 block w-full"
              v-model="form.telp"
              required
              :placeholder="lang().label.telp"
              :error="form.errors.telp"
            />
            <InputError class="mt-2" :message="form.errors.telp" />
          </div>
          <div>
            <FwbInput v-model="form.address" :placeholder="lang().label.address" :label="lang().label.address" />
            <InputError class="mt-2" :message="form.errors.address" />
          </div>
          <div>
            <FwbInput v-model="form.address_en" :placeholder="lang().label.address_en" :label="lang().label.address_en" />
            <InputError class="mt-2" :message="form.errors.address_en" />
          </div>
          <div>
            <FwbTextarea rows="2" :placeholder="lang().label.maps" v-model="form.maps" :label="lang().label.maps" />
            <InputError class="mt-2" :message="form.errors.maps" />
          </div>
          <div>
            <FwbInput
              v-model="form.link"
              :placeholder="lang().label.link"
              :label="lang().label.link"
            />
            <InputError class="mt-2" :message="form.errors.link" />
          </div>
          <div>
            <FwbInput
              v-model="form.email"
              :placeholder="lang().label.email"
              :label="lang().label.email"
            />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>
          <div>
            <FwbInput
              v-model="form.facebook"
              :placeholder="lang().label.facebook"
              :label="lang().label.facebook"
            />
            <InputError class="mt-2" :message="form.errors.facebook" />
          </div>
          <div>
            <FwbInput
              v-model="form.instagram"
              :placeholder="lang().label.instagram"
              :label="lang().label.instagram"
            />
            <InputError class="mt-2" :message="form.errors.instagram" />
          </div>
          <div>
            <FwbInput
              v-model="form.tiktok"
              :placeholder="lang().label.tiktok"
              :label="lang().label.tiktok"
            />
            <InputError class="mt-2" :message="form.errors.tiktok" />
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
