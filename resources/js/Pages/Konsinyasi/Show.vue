<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref, computed, watchEffect, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { priceFormat } from "../../helper.js";

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import EditAgreement from "@/Pages/Konsinyasi/EditAgreement.vue";

import {
  FwbTextarea,
  FwbFileInput,
  FwbInput,
  FwbTab,
  FwbTabs,
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
  FwbButton,
  FwbSelect,
} from "flowbite-vue";
import DatePicker from "vue-datepicker-next";
import "vue-datepicker-next/index.css";

import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const props = defineProps({
  title: String,
  breadcrumbs: Object,
  product: Object,
  approveFile: Object,
  cancelFile: Object,
  vendorProductStatus: Object,
});

const form = useForm({
  confirm_date: "",
  update_status: "",
  note:"",
  agreement:props.product.agreements
});

const status = ref('');

const formComplete = useForm({
  id:props.product.id
});

const formatter = ref({
  date: "DD-MM-YYYY",
  month: "MMM",
});

const vendorProductStatus = Object.values(props.vendorProductStatus).map(
  (data) => ({
    name: data,
    value: data,
  })
);

const update = () => {
  form.put(route("konsinyasi.update", props.product?.id), {
    preserveScroll: true,
    onSuccess: () => {
      // form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};

const updateAgreement = () => {
  form.post(route("konsinyasi.update-agreement"), {
    preserveScroll: true,
    onSuccess: () => {
    },
    onError: () => null,
    onFinish: () => null,
  });
};

const setComplete = () => {
  formComplete.post(route("konsinyasi.complete"), {
    preserveScroll: true,
    onSuccess: () => {
    },
    onError: () => null,
    onFinish: () => null,
  });
};

onMounted(() => {
  form.confirm_date = props.product?.confirm_date ? props.product?.confirm_date : '';
  form.note = props.product?.note;

  status.value = props.product?.status;

  form.errors = {};
});

const activeTab = ref("product");

const data = reactive({
    editOpen: false,
    agreement:null
});

const isCompleted = computed(() => {
  
  let complete = props.product.agreements.every(item => item.is_approved == true);
  
  return complete;
});

const isUpload = computed(() => {
  
  let complete = props.product.agreements.every(item => item.file != null);
  
  return complete;
});

const canotSave = computed(() => {
  
  let complete = props.product.status == 'Canceled' || props.product.status == 'Completed' || props.product.status == 'Approved';
  
  return complete;
});

</script>

<template>
  <Head :title="props.title" />
  <AuthenticatedLayout>
    <Breadcrumb :breadcrumbs="breadcrumbs" />

    <edit-agreement
        :show="data.editOpen"
        @close="data.editOpen = false"
        :agreement="data.agreement"
        :title="props.title"
    />

    <div
      class="grid grid-cols-1 mb-10 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900"
    >
      <div class="col-span-3">
        <div
          class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800"
        >
          <div class="mb-5 grid grid-cols-12 gap-6">
            <div class="col-span-12">
              <h3 class="text-xl font-semibold dark:text-white">
                {{ lang().label.vendor }}
              </h3>
            </div>
            <div class="col-span-6">
              <FwbInput
                readonly
                v-model="props.product.vendor.name"
                :placeholder="lang().label.name"
                :label="lang().label.name"
              />
            </div>
            <div class="col-span-6">
              <FwbInput
                readonly
                v-model="props.product.vendor.bank_account_holder"
                :placeholder="lang().label.bank_account_holder"
                :label="lang().label.bank_account_holder"
              />
            </div>
            <div class="col-span-6">
              <FwbInput
                readonly
                v-model="props.product.vendor.ktp"
                :placeholder="lang().label.ktp"
                :label="lang().label.ktp"
              />
            </div>
            <div class="col-span-6">
              <FwbInput
                readonly
                v-model="props.product.vendor.bank_account_number"
                :placeholder="lang().label.bank_account_number"
                :label="lang().label.bank_account_number"
              />
            </div>
            <div class="col-span-6">
              <FwbInput
                readonly
                v-model="props.product.vendor.bank"
                :placeholder="lang().label.bank"
                :label="lang().label.bank"
              />
            </div>

            <div class="col-span-6">
              <FwbInput
                readonly
                v-model="props.product.status"
                :placeholder="lang().label.status"
                :label="lang().label.status"
              />
            </div>
          </div>

          <fwb-tabs v-model="activeTab" variant="underline" class="mt-5">
            <fwb-tab name="product" :title="lang().label.product">
              <div class="grid grid-cols-12 gap-6">
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.name"
                    :placeholder="lang().placeholder.name"
                    :label="lang().placeholder.name"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.brand.name"
                    :placeholder="lang().label.brand"
                    :label="lang().label.brand"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.product_category.name"
                    :placeholder="lang().label.product_category"
                    :label="lang().label.product_category"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.color.name"
                    :placeholder="lang().label.color"
                    :label="lang().label.color"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.weight"
                    :placeholder="lang().label.weight"
                    :label="lang().label.weight"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.length"
                    :placeholder="lang().label.length"
                    :label="lang().label.length"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.width"
                    :placeholder="lang().label.width"
                    :label="lang().label.width"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.height"
                    :placeholder="lang().label.height"
                    :label="lang().label.height"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.price"
                    :placeholder="lang().label.price"
                    :label="lang().label.price"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.price_usd"
                    :placeholder="lang().label.price_usd"
                    :label="lang().label.price_usd"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.commission_type"
                    :placeholder="lang().label.commission_type"
                    :label="lang().label.commission_type"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.commission"
                    :placeholder="lang().label.commission"
                    :label="lang().label.commission"
                  />
                </div>
                <div class="col-span-6">
                  <FwbTextarea
                    rows="4"
                    readonly
                    :placeholder="lang().label.description"
                    v-model="props.product.description"
                    :label="lang().label.description"
                  />
                </div>
                <div class="col-span-6">
                  <FwbTextarea
                    rows="4"
                    readonly
                    :placeholder="lang().label.description_en"
                    v-model="props.product.description_en"
                    :label="lang().label.description_en"
                  />
                </div>
                <div class="col-span-6">
                  <FwbTextarea
                    rows="4"
                    readonly
                    :placeholder="lang().label.history"
                    v-model="props.product.history"
                    :label="lang().label.history"
                  />
                </div>
                <div class="col-span-6">
                  <FwbTextarea
                    rows="4"
                    readonly
                    :placeholder="lang().label.history_en"
                    v-model="props.product.history_en"
                    :label="lang().label.history_en"
                  />
                </div>
                <div class="col-span-6">
                  <FwbInput
                    readonly
                    v-model="props.product.condition"
                    :placeholder="lang().label.condition"
                    :label="lang().label.condition"
                  />
                </div>
              </div>
              <div
                v-if="props.product.imageable.length > 0"
                class="grid grid-cols-2 mt-5 md:grid-cols-3 gap-4"
              >
                <div
                  v-for="(image, index) in props.product.imageable"
                  :key="index"
                >
                  <img
                    class="h-auto max-w-full rounded-lg"
                    :src="`/image/` + image.name"
                    alt=""
                  />
                </div>
              </div>                               
            </fwb-tab>
            <fwb-tab name="approval" :title="lang().label.approval">
              <form @submit.prevent="update">
                <div class="grid grid-cols-12 gap-6 mt-5">
                  <div class="col-span-6">
                    <InputLabel
                      for="confirm_date"
                      :value="lang().label.pickup_date"
                    />
                    <vue-tailwind-datepicker
                      readonly
                      v-model="form.confirm_date"
                      :formatter="formatter"
                      :placeholder="lang().label.pickup_date"
                      as-single
                    />
                    <InputError
                      class="mt-2"
                      :message="form.errors.confirm_date"
                    />
                  </div>
                  <div class="col-span-6">
                    <FwbSelect
                      v-model="form.update_status"
                      :options="vendorProductStatus"
                      label="Update Status"
                    />

                    <InputError class="mt-2" :message="form.errors.status" />
                  </div>
                  <div class="col-span-6">
                    <FwbTextarea
                      rows="4"
                      readonly
                      :placeholder="lang().label.note"
                      v-model="form.note"
                      :label="lang().label.note"
                    />
                  </div>
                  <div class="col-span-6">
                  </div>

                  <div class="col-span-12" v-if="props.approveFile && props.product.status == 'Review'">
                    <h3 class="text-md font-semibold dark:text-white">
                      Approval File
                    </h3>
                    <fwb-table class="mt-3">
                      <fwb-table-head>
                        <fwb-table-head-cell>{{ lang().label.file_vendor }}</fwb-table-head-cell>
                        <fwb-table-head-cell></fwb-table-head-cell>
                      </fwb-table-head>
                      <fwb-table-body>
                        <fwb-table-cell class="text-left">
                          <fwb-a v-if="props.product.approve_file"  :href="props.product.approve_file_url" target="_blank">
                            Show
                          </fwb-a>
                          <span v-else>{{ 'Belum diupload' }}</span>
                        </fwb-table-cell>
                        <fwb-table-cell></fwb-table-cell>
                      </fwb-table-body>
                    </fwb-table>
                  </div>
                  <!-- <div class="col-span-12" v-else>
                    <h3 class="text-md font-semibold text-red-600 dark:text-white">
                      File Approval belum diupload
                    </h3>
                  </div> -->
                  <div class="col-span-12" v-if="(props.cancelFile && props.product.status == 'Not Approved') || props.product.status=='Canceled'">
                    <h3 class="text-md font-semibold dark:text-white">
                      Cancel File
                    </h3>
                    <fwb-table class="mt-3">
                      <fwb-table-head>
                        <fwb-table-head-cell>{{ lang().label.file_vendor }}</fwb-table-head-cell>
                        <fwb-table-head-cell></fwb-table-head-cell>
                      </fwb-table-head>
                      <fwb-table-body>
                        <fwb-table-cell class="text-left">
                          <fwb-a v-if="props.product.cancel_file"  :href="props.product.cancel_file_url" target="_blank">
                            Show
                          </fwb-a>
                          <span v-else>{{ 'Belum diupload' }}</span>
                        </fwb-table-cell>
                        <fwb-table-cell></fwb-table-cell>
                      </fwb-table-body>
                    </fwb-table>
                  </div>
                  <div
                   
                    class="flex justify-start gap-2 col-span-6 sm:col-full"
                  >
                    <PrimaryButton v-if="!canotSave"
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
            </fwb-tab>
            <fwb-tab v-if="form.agreement.length > 0" name="agreement" :title="lang().label.agreement">
              <fwb-table>
                <fwb-table-head>
                  <fwb-table-head-cell>No</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.name }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.draft }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.file_vendor }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.status }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.note }}</fwb-table-head-cell>
                  <fwb-table-head-cell>
                    <span class="sr-only">Edit</span>
                  </fwb-table-head-cell>
                </fwb-table-head>
                <fwb-table-body>
                  <fwb-table-row  v-for="(agreement, index) in form.agreement"
                        :key="index">
                    <fwb-table-cell>{{ ++index }}</fwb-table-cell>
                    <fwb-table-cell>{{ agreement.agreement.name }}</fwb-table-cell>
                    <fwb-table-cell>
                      <fwb-a v-if="agreement.draft" :href="agreement.draft" target="_blank">
                        Show
                      </fwb-a>
                      <span v-else>{{ '-' }}</span>
                    </fwb-table-cell>
                    <fwb-table-cell>
                      <fwb-a v-if="agreement.file_url" :href="agreement.file_url" target="_blank">
                        Show
                      </fwb-a>
                      <span v-else>{{ 'Belum diupload' }}</span>
                    </fwb-table-cell>
                    <fwb-table-cell>{{ agreement.is_approved ? 'Approve' : 'Not Approve' }}</fwb-table-cell>
                    <fwb-table-cell>{{ agreement.note }}</fwb-table-cell>
                    <fwb-table-cell>
                      <fwb-button v-if="agreement.file_url && props.product.status != 'Completed' " type="button" size="xs" class="mr-2" @click="(data.editOpen = true),
                            (data.agreement = agreement)" color="default">Edit</fwb-button>
                    </fwb-table-cell>
                  </fwb-table-row>
                </fwb-table-body>
              </fwb-table>
              <div>
                <form v-if="!isCompleted && isUpload" @submit.prevent="updateAgreement">
                  <div class="flex justify-end gap-2 col-span-6 mt-5 sm:col-full">
                    <PrimaryButton
                      type="submit"
                      :class="{
                        'opacity-25': form.processing,
                      }"
                      :disabled="form.processing"
                    >
                      {{
                        form.processing
                          ? lang().button.save + "..."
                          : lang().button.save
                      }}
                    </PrimaryButton>                  
                  </div>
                </form>

                <form v-if="isCompleted && props.product.status != 'Completed'" @submit.prevent="setComplete">
                  <div class="flex justify-end gap-2 col-span-6 mt-5 sm:col-full">
                    <PrimaryButton
                      type="submit"
                      :class="{
                        'opacity-25': form.processing,
                      }"
                      :disabled="form.processing"
                    >
                      {{
                        form.processing
                          ? lang().label.completed + "..."
                          : lang().label.completed
                      }}
                    </PrimaryButton>                  
                  </div>
                </form>
              </div>
            </fwb-tab>
          </fwb-tabs>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

