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
import { 
  FwbTextarea, FwbFileInput, FwbInput, FwbSelect, 
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
  FwbButton

} from "flowbite-vue";
import { priceFormat, calculatePpn, round, rupiah } from "../../helper.js";
import Multiselect from "@vueform/multiselect";

const { _, debounce, pickBy } = pkg;

const props = defineProps({
  title: String,
  nomor: String,
  pendaftaranAsset: Object,
  breadcrumbs: Object,
 
});

const form = useForm({
  nomor: props.nomor,
  tanggal: '',
  pendaftaran_asset_id: '',
  metode_penyusutan:'',
  nilai_jual:'',
});

const pendaftaranAsset = props.pendaftaranAsset.map((data) => ({
    label: data.nomor,
    value: data.id
}))

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const data = reactive({
   asset:'',   
   group_asset:'',   
});

const create = () => {
  form.post(route("pendaftaran-asset.store"), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};


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

watch(
    () => _.cloneDeep(form.pendaftaran_asset_id),
    debounce(() => {
      
      // form.asset_id = '';
      // asset.value = [];

      if(form.pendaftaran_asset_id){
        let selected = props.pendaftaranAsset.find(item => item.id == form.pendaftaran_asset_id);
        data.asset = selected.asset.name;
        data.group_asset = selected.group_asset.name;
      }
      
    }, 150)
);

const tanggal = moment(new Date());

onMounted(() => {
  form.tanggal = tanggal.format("DD-MM-YYYY");
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
          <form @submit.prevent="create">
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
                <InputLabel for="pendaftaran_asset" :value="lang().label.pendaftaran_asset" />

                <Multiselect
                  id="pendaftaran_asset"
                  v-model="form.pendaftaran_asset_id"
                  :options="pendaftaranAsset"
                  track-by="label"
                  label="label"
                  :searchable="true"
                  placeholder="Select"
                />

                <InputError class="mt-2" :message="form.errors.pendaftaran_asset_id" />
              </div>
              <div class="col-span-6">                
                  <FwbInput readonly v-model="data.group_asset" :placeholder="lang().label.group_asset" :label="lang().label.group_asset" />
                  <InputError class="mt-2" :message="form.errors.group_asset_id" />                                
              </div>
              <div class="col-span-6">                
                  <FwbInput readonly v-model="data.asset" :placeholder="lang().label.asset" :label="lang().label.asset" />
                  <InputError class="mt-2" :message="form.errors.asset_id" />                                
              </div>
              <div class="col-span-6">                
                  <FwbInput v-model="form.nilai_jual" :placeholder="lang().label.nilai_jual" :label="lang().label.nilai_jual" />
                  <InputError class="mt-2" :message="form.errors.nilai_jual" />                                
              </div>
              

              <div class="flex justify-start gap-2 col-span-6 sm:col-full">
                <PrimaryButton
                  type="submit"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  {{
                    form.processing
                      ? lang().button.add + "..."
                      : lang().button.add
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

