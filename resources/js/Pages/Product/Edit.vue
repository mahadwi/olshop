<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
// import FilePondInput from '@/Components/FilePondInput.vue'
import vueFilePond, { setOptions } from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import axios from "axios";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

import { FwbTextarea, FwbFileInput, FwbInput } from "flowbite-vue";
import { priceFormat } from '../../helper.js'

const props = defineProps({
  show: Boolean,
  title: String,
  product: Object,
  categories: Object,
  vendors: Object,
  brands: Object,
  colors: Object,
  condition: Object,
  breadcrumbs: Object,
  commissionType: Object,
  images: Object,
});

const form = useForm({
  name: props.product.name,
  brand_id: props.product.brand_id,
  description: props.product.description,
  product_category_id: props.product.product_category_id,
  vendor_id: props.product.vendor_id,
  stock: props.product.stock,
  price: priceFormat(props.product.price),
  price_usd: props.product.price_usd,
  sale_price: priceFormat(props.product.sale_price),
  sale_usd: props.product.sale_usd,
  commission: props.product.commission,
  commission_type: props.product.commission_type,
  display_on_homepage: props.product.display_on_homepage,
  history: props.product.history,
  history_en: props.product.history_en,
  entry_date: props.product.entry_date,
  expired_date: props.product.expired_date,
  description_en: props.product.description_en,
  color_id: props.product.color_id,
  condition: props.product.condition,
  weight: props.product.weight,
  length: props.product.length,
  width: props.product.width,
  height: props.product.height,
});

const filepondRef = ref();
const images = ref([]);
const page = usePage();

const filepond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview
);

// Set global options on filepond init
const handleFilePondInit = () => {
  setOptions({
    server: {
      load: (source, load, error, progress, abort, headers) => {
        // axios.get(route('product.get-image', source)).then(res).then(load);
        let request = new XMLHttpRequest();
        request.open("GET", route("product.get-image", source));
        request.responseType = "blob";
        request.onreadystatechange = () =>
          request.readyState === 4 && load(request.response);
        request.send();
      },
      process: (fieldName, file, metadata, load, error, progress, abort) => {
        const formData = new FormData();
        formData.append(fieldName, file, file.name);

        const request = new XMLHttpRequest();
        request.open("POST", route("product.upload-image", props.product?.id));
        request.setRequestHeader("X-CSRF-TOKEN", page.props.token);

        request.upload.onprogress = (e) => {
          progress(e.lengthComputable, e.loaded, e.total);
        };

        request.onload = function () {
          if (request.status >= 200 && request.status < 300) {
            load(request.responseText);
          } else {
            error("Error");
          }
        };

        request.send(formData);
        return {
          abort: () => {
            request.abort();
            abort();
          },
        };
      },
      revert: (src, load) => {
        axios.post(route("product.delete-image", props.product?.id), {
          name: src,
        });
        load();
      },
      remove: (src, load) => {
        axios.post(route("product.delete-image", props.product?.id), {
          name: src,
        });
        load();
      },
      headers: { "X-CSRF-TOKEN": page.props.token },
    },
    files: props.images,
  });
};

const categories = props.categories?.map((role) => ({
  label: role.name,
  value: role.id,
}));

const vendors = props.vendors?.map((role) => ({
  label: role.name,
  value: role.id,
}));

const brands = props.brands?.map((role) => ({
  label: role.name,
  value: role.id,
}));

const colors = props.colors?.map((role) => ({
  label: role.name,
  value: role.id,
}));

const commissionType = Object.values(props.commissionType).map((data) => ({
  label: data,
  value: data,
}));

const condition = Object.values(props.condition).map((data) => ({
  label: data,
  value: data,
}));

const dataSwitch = [
  {
    label: "Yes",
    value: true,
  },
  {
    label: "No",
    value: false,
  },
];

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const update = () => {
  form.post(route("product.update", props.product?.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};

const changeCommission = () => {
  form.sale_price = form.price;
  form.sale_usd = form.price_usd

  if (form.commission_type == "Selling") {
    form.commission = 0;

    if (form.sale_price == 0) form.sale_price = form.price;
    if (form.sale_usd == 0) form.sale_usd = form.price_usd;
  }
};

const formatUang = (e) => {
    let angka = parseFloat(form.price.replace(/[^\d]/g, '')) || 0;
    let bilangan = String(angka);
      let hasil = '';
      let count = 0;

      for (let i = bilangan.length - 1; i >= 0; i--) {
        hasil = bilangan[i] + hasil;
        count++;

        if (count === 3 && i > 0) {
          hasil = '.' + hasil;
          count = 0;
        }
      }

      form.price = hasil
}

const formatUangSale = (e) => {
    let angka = parseFloat(e.target.value.replace(/[^\d]/g, '')) || 0;
    let bilangan = String(angka);
      let hasil = '';
      let count = 0;

      for (let i = bilangan.length - 1; i >= 0; i--) {
        hasil = bilangan[i] + hasil;
        count++;

        if (count === 3 && i > 0) {
          hasil = '.' + hasil;
          count = 0;
        }
      }

      form.sale_price = hasil
}

const formatUangDolar = (e) => {
    let cleanedValue = e.target.value.replace(/[^\d.]/g, '');
    form.price_usd = cleanedValue;
}

const formatUangDolarSale = (e) => {
    let cleanedValue = e.target.value.replace(/[^\d.]/g, '');
    form.sale_usd = cleanedValue;
}

</script>

<template>
  <Head :title="props.title" />
  <AuthenticatedLayout>
    <Breadcrumb :breadcrumbs="breadcrumbs" />

    <div
      class="grid grid-cols-1 mb-10 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900"
    >
      <div class="col-span-3">
        <div
          class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800"
        >
          <h3 class="mb-4 text-xl font-semibold dark:text-white">
            {{ props.title }}
          </h3>
          <form @submit.prevent="update">
            <div class="grid grid-cols-12 gap-6">
              <div class="col-span-6">
                <FwbInput
                  v-model="form.name"
                  :placeholder="lang().placeholder.name"
                  :label="lang().placeholder.name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
              </div>
              <div class="col-span-6">
                <InputLabel for="brand" :value="lang().label.brand" />
                <SelectInput
                  id="brand"
                  class="mt-1 block w-full"
                  v-model="form.brand_id"
                  :dataSet="brands"
                >
                </SelectInput>
                <InputError class="mt-2" :message="form.errors.brand_id" />
              </div>
              <div class="col-span-6">
                <InputLabel for="entry_date" :value="lang().label.entry_date" />
                <vue-tailwind-datepicker
                  v-model="form.entry_date"
                  :formatter="formatter"
                  :placeholder="lang().label.entry_date"
                  as-single
                />
                <InputError class="mt-2" :message="form.errors.entry_date" />
              </div>
              <div class="col-span-6">
                <InputLabel
                  for="expired_date"
                  :value="lang().label.expired_date"
                />
                <vue-tailwind-datepicker
                  v-model="form.expired_date"
                  :formatter="formatter"
                  :placeholder="lang().label.expired_date"
                  as-single
                />
                <InputError class="mt-2" :message="form.errors.expired_date" />
              </div>
              <div class="col-span-6">
                <InputLabel
                  for="product_category"
                  :value="lang().label.product_category"
                />
                <SelectInput
                  id="product_category"
                  class="mt-1 block w-full"
                  v-model="form.product_category_id"
                  :dataSet="categories"
                >
                </SelectInput>
                <InputError
                  class="mt-2"
                  :message="form.errors.product_category_id"
                />
              </div>

              <div class="col-span-6">
                <InputLabel for="vendor" :value="lang().label.vendor" />
                <SelectInput
                  id="vendor"
                  class="mt-1 block w-full"
                  v-model="form.vendor_id"
                  :dataSet="vendors"
                >
                </SelectInput>
                <InputError class="mt-2" :message="form.errors.vendor_id" />
              </div>

              <div class="col-span-6">
                <FwbInput
                  v-model="form.weight"
                  :placeholder="lang().label.weight"
                  :label="lang().label.weight"
                />
                <InputError class="mt-2" :message="form.errors.weight" />
              </div>

							<div class="col-span-6">
                <FwbInput
                  v-model="form.length"
                  :placeholder="lang().label.length"
                  :label="lang().label.length"
                />
                <InputError class="mt-2" :message="form.errors.length" />
              </div>

							<div class="col-span-6">
                <FwbInput
                  v-model="form.width"
                  :placeholder="lang().label.width"
                  :label="lang().label.width"
                />
                <InputError class="mt-2" :message="form.errors.width" />
              </div>

							<div class="col-span-6">
                <FwbInput
                  v-model="form.height"
                  :placeholder="lang().label.height"
                  :label="lang().label.height"
                />
                <InputError class="mt-2" :message="form.errors.height" />
              </div>

              <div class="col-span-6">
                <FwbInput
                  v-model="form.stock"
                  :placeholder="lang().label.stock"
                  :label="lang().label.stock"
                />
                <InputError class="mt-2" :message="form.errors.stock" />
              </div>

              <div class="col-span-6">
                <InputLabel for="price" :value="lang().label.price" />
                <FwbInput
                  id="price"
                  class="mt-1 block w-full"
                  v-model="form.price"
                  :placeholder="lang().label.price"
                  :error="form.errors.price"
                  @input="formatUang"
                />
                <InputError class="mt-2" :message="form.errors.price" />
              </div>
            <div class="col-span-6">

            </div>

            <div class="col-span-6">
                <InputLabel for="price_usd" :value="lang().label.price_usd" />
                <FwbInput
                    id="price_usd"
                    class="mt-1 block w-full"
                    v-model="form.price_usd"
                    :placeholder="lang().label.price_usd"
                    :error="form.errors.price_usd"
                    @input="formatUangDolar"
                />
                <InputError class="mt-2" :message="form.errors.price_usd" />
            </div>

              <div class="col-span-6">
                <InputLabel
                  for="commission_type"
                  :value="lang().label.commission_type"
                />
                <SelectInput
                  id="commission_type"
                  class="mt-1 block w-full"
                  v-model="form.commission_type"
                  :dataSet="commissionType"
                  @change="changeCommission()"
                >
                </SelectInput>
                <InputError
                  class="mt-2"
                  :message="form.errors.commission_type"
                />
              </div>
              <div class="col-span-6">
                <FwbInput
                  :disabled="form.commission_type == 'Percent'"
                  v-model="form.sale_price"
                  :placeholder="lang().label.sale_price"
                  :label="lang().label.sale_price"
                  @input="formatUangSale"
                />
                <InputError class="mt-2" :message="form.errors.sale_price" />
              </div>
              <div class="col-span-6">
              </div>
              <div class="col-span-6">
                <FwbInput
                  :disabled="form.commission_type == 'Percent'"
                  v-model="form.sale_usd"
                  :placeholder="lang().label.sale_usd"
                  :label="lang().label.sale_usd"
                  @input="formatUangDolarSale"
                />
                <InputError class="mt-2" :message="form.errors.sale_usd" />
              </div>
              <div class="col-span-6">
                <FwbInput
                  :disabled="form.commission_type == 'Selling'"
                  v-model="form.commission"
                  :placeholder="lang().label.commission"
                  :label="lang().label.commission"
                />
                <InputError class="mt-2" :message="form.errors.commission" />
              </div>
              <div class="col-span-6">
                <InputLabel
                  for="display_on_homepage"
                  :value="lang().label.display_on_homepage"
                />
                <SelectInput
                  id="display_on_homepage"
                  class="mt-1 block w-full"
                  v-model="form.display_on_homepage"
                  :dataSet="dataSwitch"
                >
                </SelectInput>
                <InputError
                  class="mt-2"
                  :message="form.errors.display_on_homepage"
                />
              </div>
              <div class="col-span-6">
                <InputLabel for="color" :value="lang().label.color" />
                <SelectInput
                  id="color"
                  class="mt-1 block w-full"
                  v-model="form.color_id"
                  :dataSet="colors"
                >
                </SelectInput>
                <InputError class="mt-2" :message="form.errors.color_id" />
              </div>
              <div class="col-span-6">
                <InputLabel for="condition" :value="lang().label.condition" />
                <SelectInput
                  id="condition"
                  class="mt-1 block w-full"
                  v-model="form.condition"
                  :dataSet="condition"
                >
                </SelectInput>
                <InputError class="mt-2" :message="form.errors.condition" />
              </div>
              <div class="col-span-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description}} </label>
                <QuillEditor theme="snow" toolbar="essential" content-type="html" :placeholder="lang().label.description" v-model:content="form.description" />
                <InputError class="mt-2" :message="form.errors.description" />
              </div>
              <div class="col-span-6">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"> {{lang().label.description_en}} </label>
                <QuillEditor theme="snow" toolbar="essential" content-type="html" :placeholder="lang().label.description_en" v-model:content="form.description_en" />
                <InputError class="mt-2" :message="form.errors.description_en" />
              </div>
              <div class="col-span-6 mt-20">
                <FwbTextarea
                  rows="4"
                  :placeholder="lang().label.history"
                  v-model="form.history"
                  :label="lang().label.history"
                />
                <InputError class="mt-2" :message="form.errors.history" />
              </div>
              <div class="col-span-6 mt-20">
                <FwbTextarea
                  rows="4"
                  :placeholder="lang().label.history_en"
                  v-model="form.history_en"
                  :label="lang().label.history_en"
                />
                <InputError class="mt-2" :message="form.errors.history_en" />
              </div>

              <div class="col-span-12 mt-20">
                <filepond
                  name="image"
                  ref="filepondRef"
                  label-idle="Upload Images..."
                  :allow-multiple="true"
                  accepted-file-types="image/jpeg, image/png"
                  maxFileSize="1MB"
                  :files="images"
                  @init="handleFilePondInit"
                />
              </div>

              <div class="flex justify-start gap-2 col-span-6 sm:col-full">
                <PrimaryButton
                  type="submit"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  {{
                    form.processing
                      ? lang().button.save + "..."
                      : lang().button.save
                  }}
                </PrimaryButton>
                <SecondaryButton
                  :disabled="form.processing"
                  @click="form.reset()"
                >
                  Reset
                </SecondaryButton>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


