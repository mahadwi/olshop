<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import pkg from "lodash";
import moment from 'moment';

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { FwbTextarea, FwbFileInput, FwbInput, FwbSelect } from "flowbite-vue";
import { priceFormat, calculatePpn } from "../../helper.js";
import Multiselect from "@vueform/multiselect";

const { _, debounce, pickBy } = pkg;

const props = defineProps({
  title: String,
  groupAsset: Object,
  vendor: Object,
  breadcrumbs: Object,
  jenisPpn: Object,
  ppn: Number,
  pembelianAsset: Object,
});

const form = useForm({
  nomor: props.pembelianAsset.nomor,
  tanggal: props.pembelianAsset.tanggal,
  vendor_id: props.pembelianAsset.vendor_id,
  asset_id: props.pembelianAsset.asset_id,
  jatuh_tempo: props.pembelianAsset.jatuh_tempo,
  tanggal_jatuh_tempo: props.pembelianAsset.tanggal_jatuh_tempo,
  group_asset_id: props.pembelianAsset.asset.group_asset_id,
  qty: props.pembelianAsset.qty,
  price: props.pembelianAsset.price,
  jenis_ppn: props.pembelianAsset.jenis_ppn,
  total: props.pembelianAsset.total,
  keterangan:props.pembelianAsset.keterangan
});

let total = computed(() => {
  
  if(form.jenis_ppn == '') return 0;

  if(form.jenis_ppn == 'Include') return form.price

  return calculatePpn(form.price, props.ppn)
})

let tglJatuhTempo = computed(() => {  
  return moment(form.tanggal, "DD-MM-YYYY").add(form.jatuh_tempo - 1, 'days').format("DD-MM-YYYY");
})

const vendor = props.vendor.map((data) => ({
    label: data.name,
    value: data.id
}))

const group_asset = props.groupAsset.map((data) => ({
    label: data.name,
    value: data.id
}))

let asset = ref([]);

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const update = () => {
  form.put(route("pembelian-asset.update", props.pembelianAsset?.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};

const jenisPpn = Object.values(props.jenisPpn).map((data) => ({
    name: data,
    value: data,
}));

const formatUang = (e) => {
  let angka = parseFloat(form.price.replace(/[^\d]/g, "")) || 0;
  let bilangan = String(angka);
  let hasil = "";
  let count = 0;

  for (let i = bilangan.length - 1; i >= 0; i--) {
    hasil = bilangan[i] + hasil;
    count++;

    if (count === 3 && i > 0) {
      hasil = "." + hasil;
      count = 0;
    }
  }

  form.price = hasil;
};

const formatUangSale = (e) => {
  let angka = parseFloat(e.target.value.replace(/[^\d]/g, "")) || 0;
  let bilangan = String(angka);
  let hasil = "";
  let count = 0;

  for (let i = bilangan.length - 1; i >= 0; i--) {
    hasil = bilangan[i] + hasil;
    count++;

    if (count === 3 && i > 0) {
      hasil = "." + hasil;
      count = 0;
    }
  }

  form.sale_price = hasil;
};

const formatUangDolar = (e) => {
  let cleanedValue = e.target.value.replace(/[^\d.]/g, "");
  form.price_usd = cleanedValue;
};

const getAsset = () => {
  let dataAsset = props.groupAsset.find(item => item.id == form.group_asset_id);
        
  asset.value = dataAsset.assets.map((data) => ({
      label: data.name,
      value: data.id
  }))
}

watch(
    () => _.cloneDeep(form.group_asset_id),
    debounce(() => {
      
      form.asset_id = '';
      asset.value = [];

      if(form.group_asset_id){
        getAsset();
      }
      
    }, 150)
);

const tanggal = moment(new Date());

onMounted(() => {
  form.total = total;
  form.tanggal = props.pembelianAsset.tanggal;
  form.tanggal_jatuh_tempo = tglJatuhTempo;

  getAsset();
})

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
                  <FwbInput readonly v-model="form.nomor" :placeholder="lang().label.nomor" :label="lang().label.nomor" />
                  <InputError class="mt-2" :message="form.errors.nomor" />                                
              </div>
              <div class="col-span-6">
                <InputLabel
                  for="date"
                  :value="lang().label.date"
                />
                <vue-tailwind-datepicker
                  readonly
                  v-model="form.tanggal"
                  :formatter="formatter"
                  :placeholder="lang().label.date"
                  as-single
                />
                <InputError class="mt-2" :message="form.errors.tanggal" />
              </div>              
              <div class="col-span-6">                
                  <FwbInput v-model="form.jatuh_tempo" :placeholder="lang().label.jatuh_tempo" :label="lang().label.jatuh_tempo" />
                  <InputError class="mt-2" :message="form.errors.jatuh_tempo" />                                
              </div>
              <div class="col-span-6">                
                  <FwbInput v-model="form.tanggal_jatuh_tempo" readonly :placeholder="lang().label.tgl_jatuh_tempo" :label="lang().label.tgl_jatuh_tempo" />
                  <InputError class="mt-2" :message="form.errors.tanggal_jatuh_tempo" />                                
              </div>
              <div class="col-span-6">
                <InputLabel for="vendor" :value="lang().label.vendor" />

                <Multiselect
                  id="vendor"
                  v-model="form.vendor_id"
                  :options="vendor"
                  track-by="label"
                  label="label"
                  :searchable="true"
                  placeholder="Select"
                />

                <InputError class="mt-2" :message="form.errors.vendor_id" />
              </div>
              <div class="col-span-6">
                <InputLabel for="group_asset" :value="lang().label.group_asset" />

                <Multiselect
                  id="group_asset"
                  v-model="form.group_asset_id"
                  :options="group_asset"
                  track-by="label"
                  label="label"
                  :searchable="true"
                  placeholder="Select"
                />

                <InputError class="mt-2" :message="form.errors.group_asset_id" />
              </div>
              <div class="col-span-6">
                <InputLabel for="asset" :value="lang().label.asset" />

                <Multiselect
                  id="asset"
                  v-model="form.asset_id"
                  :options="asset"
                  track-by="label"
                  label="label"
                  :searchable="true"
                  placeholder="Select"
                />

                <InputError class="mt-2" :message="form.errors.asset_id" />
              </div>
              <div class="col-span-6">                
                  <FwbInput v-model="form.qty" readonly :placeholder="lang().label.qty" :label="lang().label.qty" />
                  <InputError class="mt-2" :message="form.errors.qty" />                                
              </div>
              <div class="col-span-6">                
                  <FwbInput v-model="form.price" :placeholder="lang().label.price" :label="lang().label.price" />
                  <InputError class="mt-2" :message="form.errors.price" />                                
              </div>
              <div class="col-span-6">
                <FwbSelect
                  v-model="form.jenis_ppn"
                  :options="jenisPpn"
                  :label="lang().label.jenis_ppn"
                  :disabled="form.price == ''"
                />

                <InputError class="mt-2" :message="form.errors.jenis_ppn" />
              </div>
              <div class="col-span-6">                
                  <FwbInput v-model="total" readonly :placeholder="lang().label.total" :label="lang().label.total" />
                  <InputError class="mt-2" :message="form.errors.total" />                                
              </div>
              <div class="col-span-6">
                <FwbTextarea
                  rows="4"
                  :placeholder="lang().label.keterangan"
                  v-model="form.keterangan"
                  :label="lang().label.keterangan"
                />
                <InputError class="mt-2" :message="form.errors.keterangan" />
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
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>

