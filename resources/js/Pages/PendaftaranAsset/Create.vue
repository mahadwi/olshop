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
  pembelianAsset: Object,
  breadcrumbs: Object,
 
});

const form = useForm({
  nomor: props.nomor,
  tanggal: '',
  pembelian_asset_id: '',
  group_asset_id: '',
  asset_id: '',
  metode_penyusutan:'',
  tarif_penyusutan:'',
  umur:'',
  nilai_perolehan: 0,
  dataPenyusutan:[]
});

let data_penyusutan = ref([]);
let dataPenyusutan = ref([]);

const hitungPenyusutan = () => {

    dataPenyusutan.value = [];
    data_penyusutan.value = [];    

    let tanggal_form =  moment(form.tanggal, "DD-MM-YYYY").format("MM/DD/YYYY"),
        tarif = form.tarif_penyusutan,
        nilai = form.nilai_perolehan,
        tanggal_beli = new Date(tanggal_form),
        bulan_beli = tanggal_beli.getMonth(),
        metode = form.metode_penyusutan,
        umur = form.umur,
        tanggal_sekarang = new Date(),
        bulan_sekarang = tanggal_sekarang.getMonth(),
        selisih_bulan = moment(tanggal_sekarang).diff(moment(tanggal_beli), 'months', true),
        selisih_tahun = moment(tanggal_sekarang).format("YYYY") - moment(tanggal_beli).format("YYYY"),
        bulan = bulan_beli,
        penyusutan = 0,
        temp_penyusutan = (tarif * nilai) / 12,
        temp_nilai = nilai,
        temp_year = 0,
        history_penyusutan = [],
        data_tahun = [],
        current_mark = '';

      let no = 1,
      iteration_month = 0;
      
      for (var n = 0; n <= umur; n++) {
                    
          let iteration_date = moment(tanggal_beli).add(iteration_month, "month"),
              sisa_bulan = 12 - iteration_date.format("M"),
              bulan_kosong = 12 - sisa_bulan;

          let dataPush = {
            year:iteration_date.format("YYYY"),
            data:[],
            nilai:0,
            penyusutan:0
          };

          if (metode == "Double Decline") {
              if (n < umur && iteration_month <= ((umur * 12) - 13)) {
                  temp_penyusutan = (tarif * nilai) / 12;
              } else if (n == umur - 1 && iteration_month == ((umur * 12) - 12) && moment(tanggal_beli).format("MM") ==
                  1) {
                  // console.log(umur + "==" + n + "umur");
                  temp_penyusutan = nilai / 12;
              }
              // console.log(iteration_month + "==" + ((umur * 12) - 13));
          }

          for (let i = 0; i <= sisa_bulan; i++) {

              if (metode == "Double Decline") {
                  if (iteration_month >= umur * 12) {
                      temp_penyusutan = data_penyusutan[(umur * 12 - 1)].nilai / (13 - moment(tanggal_beli).format("MM"));
                  }
                  penyusutan += temp_penyusutan;
                  data_penyusutan.value.push({
                      "tanggal": moment(tanggal_beli).add(iteration_month, 'month').endOf('month').format(
                          "YYYY-MM-DD"),
                      "penyusutan": (nilai - penyusutan) < 0 ? nilai - (penyusutan - temp_penyusutan) :
                          temp_penyusutan,
                      "nilai": (nilai - penyusutan) < 0 ? 0 : (nilai - penyusutan)
                  });

                  
                  dataPush.data.push({
                      "tanggal": moment(tanggal_beli).add(iteration_month, 'month').endOf('month').format(
                          "YYYY-MM-DD"),
                      "penyusutan": (nilai - penyusutan) < 0 ? nilai - (penyusutan - temp_penyusutan) :
                          temp_penyusutan,
                      "nilai": (nilai - penyusutan) < 0 ? 0 : (nilai - penyusutan)
                  });
              } 

              iteration_month++;
          }


          no++;
          nilai -= penyusutan;          

          if (nilai < 0) {
              nilai = 0;
          }

          dataPush.nilai = nilai;
          dataPush.penyusutan = penyusutan;

          dataPenyusutan.value.push(dataPush);
          
          penyusutan = 0;

          if (nilai <= 0) {
              break;
          }

      }

      form.dataPenyusutan = data_penyusutan;

}

const pembelianAsset = props.pembelianAsset.map((data) => ({
    label: data.nomor,
    value: data.id
}))

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const data = reactive({
   group_asset:'',
   asset:'',   
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
    () => _.cloneDeep(form.pembelian_asset_id),
    debounce(() => {
      
      // form.asset_id = '';
      // asset.value = [];

      if(form.pembelian_asset_id){
        let selected = props.pembelianAsset.find(item => item.id == form.pembelian_asset_id);
        data.asset = selected.asset.name;
        data.group_asset = selected.asset.group_asset.name;
        form.metode_penyusutan = selected.asset.group_asset.metode_penyusutan;
        form.umur = selected.asset.group_asset.umur;
        form.tarif_penyusutan = selected.asset.group_asset.tarif_penyusutan;
        form.nilai_perolehan = selected.total;        
        form.asset_id = selected.asset.id;
        form.group_asset_id = selected.asset.group_asset_id;

        dataPenyusutan.value = [];
        data_penyusutan.value = [];
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
                <InputLabel for="pembelian_asset" :value="lang().label.pembelian_asset" />

                <Multiselect
                  id="pembelian_asset"
                  v-model="form.pembelian_asset_id"
                  :options="pembelianAsset"
                  track-by="label"
                  label="label"
                  :searchable="true"
                  placeholder="Select"
                />

                <InputError class="mt-2" :message="form.errors.pembelian_asset_id" />
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
                  <FwbInput readonly v-model="form.metode_penyusutan" :placeholder="lang().label.metode_penyusutan" :label="lang().label.metode_penyusutan" />
                  <InputError class="mt-2" :message="form.errors.metode_penyusutan" />                                
              </div>
              <div class="col-span-6">                
                  <FwbInput readonly v-model="form.umur" :placeholder="lang().label.umur" :label="lang().label.umur" />
                  <InputError class="mt-2" :message="form.errors.umur" />                                
              </div>
              <div class="col-span-6">                
                  <FwbInput readonly v-model="form.tarif_penyusutan" :placeholder="lang().label.tarif_penyusutan" :label="lang().label.tarif_penyusutan" />
                  <InputError class="mt-2" :message="form.errors.tarif_penyusutan" />                                
              </div>
              <div class="col-span-6">                
                  <FwbInput readonly v-model="form.nilai_perolehan" :placeholder="lang().label.nilai_perolehan" :label="lang().label.nilai_perolehan" />
                  <InputError class="mt-2" :message="form.errors.nilai_perolehan" />                                
              </div>
              <div class="col-span-6">                
              </div>
              <div class="flex justify-start gap-2 col-span-12 sm:col-full">
                <PrimaryButton v-if="form.pembelian_asset_id"
                  type="button" @click="hitungPenyusutan()"
                >
                  {{
                    lang().label.hitung_penyusutan
                  }}
                </PrimaryButton>
              </div>
             
              <div class="col-span-12">
                <fwb-table>
                  <fwb-table-head>
                    <fwb-table-head-cell>{{ lang().label.year }}</fwb-table-head-cell>
                    <fwb-table-head-cell>Jan</fwb-table-head-cell>
                    <fwb-table-head-cell>Feb</fwb-table-head-cell>
                    <fwb-table-head-cell>Mar</fwb-table-head-cell>
                    <fwb-table-head-cell>Apr</fwb-table-head-cell>
                    <fwb-table-head-cell>Mei</fwb-table-head-cell>
                    <fwb-table-head-cell>Jun</fwb-table-head-cell>
                    <fwb-table-head-cell>Jul</fwb-table-head-cell>
                    <fwb-table-head-cell>Agus</fwb-table-head-cell>
                    <fwb-table-head-cell>Sep</fwb-table-head-cell>
                    <fwb-table-head-cell>Okt</fwb-table-head-cell>
                    <fwb-table-head-cell>Nov</fwb-table-head-cell>
                    <fwb-table-head-cell>Des</fwb-table-head-cell>
                    <fwb-table-head-cell>Penyusutan</fwb-table-head-cell>
                    <fwb-table-head-cell>Nilai Buku</fwb-table-head-cell>
                  </fwb-table-head>
                  <fwb-table-body>
                    <template v-if="dataPenyusutan.length > 0">                      
                      <fwb-table-row v-for="(data, index) in dataPenyusutan"
                        :key="index">
                        <fwb-table-cell>{{ data.year }}</fwb-table-cell>
                        <template v-for="(detail, index) in data.data"
                        :key="index">
                          <fwb-table-cell >{{ rupiah(detail.penyusutan.toFixed(2).replace(/\./g, ',')) }}</fwb-table-cell>                      
                        </template>
                        <fwb-table-cell >{{ priceFormat(round(data.penyusutan)) }}</fwb-table-cell>                      
                        <fwb-table-cell >{{ priceFormat(round(data.nilai)) }}</fwb-table-cell>                      
                      </fwb-table-row>
                    </template>
                  </fwb-table-body>
                </fwb-table>
              </div>

              <div class="flex justify-start gap-2 col-span-6 sm:col-full">
                <PrimaryButton
                  type="submit"
                  :class="{ 'opacity-25': form.processing || dataPenyusutan.length == 0 }"
                  :disabled="form.processing || dataPenyusutan.length == 0"
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

