<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, ref } from "vue";
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

import {
  FwbInput, FwbTab, FwbTabs,
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from "flowbite-vue";

const props = defineProps({
  title: String,
  member: Object,
  breadcrumbs: Object,
});

const activeTab = ref("");
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

          <div class="grid grid-cols-12 gap-6 mb-5">
            <div class="col-span-6">
              <fwb-input
                v-model="member.userName"
                :placeholder="lang().label.user_name"
                :label="lang().label.user_name"
                readonly
              />
            </div>

            <div class="col-span-6">
              <fwb-input
                v-model="member.name"
                :placeholder="lang().placeholder.name"
                :label="lang().placeholder.name"
                readonly
              />
            </div>

            <div class="col-span-6">
              <fwb-input
                v-model="member.birthDate"
                :placeholder="lang().label.birth_date"
                :label="lang().label.birth_date"
                readonly
              />
            </div>

            <div class="col-span-6">
              <fwb-input
                v-model="member.gender"
                :placeholder="lang().label.gender"
                :label="lang().label.gender"
                readonly
              />
            </div>

            <div class="col-span-6">
              <fwb-input
                v-model="member.email"
                :placeholder="lang().placeholder.email"
                :label="lang().label.email"
                readonly
              />
            </div>

            <div class="col-span-6">
              <fwb-input
                v-model="member.phone"
                :placeholder="lang().label.no_hp"
                :label="lang().label.no_hp"
                readonly
              />
            </div>

          </div>

          <fwb-tabs v-model="activeTab" variant="underline" class="p-5">
            <fwb-tab name="address" :title="lang().label.address">
              <fwb-table v-if="props.member.addresses.length > 0">
                <fwb-table-head>
                  <fwb-table-head-cell>No</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.name }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.no_hp }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.subdistrict }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.address }}</fwb-table-head-cell>
                  <fwb-table-head-cell>{{ lang().label.primary }}</fwb-table-head-cell>
                </fwb-table-head>
                <fwb-table-body>
                  <fwb-table-row  v-for="(address, index) in member.addresses"
                        :key="index">
                    <fwb-table-cell>{{ ++index }}</fwb-table-cell>
                    <fwb-table-cell>{{ address.name }}</fwb-table-cell>
                    <fwb-table-cell>{{ address.phone }}</fwb-table-cell>
                    <fwb-table-cell>{{ address.full_address }}</fwb-table-cell>
                    <fwb-table-cell>{{ address.address }}</fwb-table-cell>
                    <fwb-table-cell>{{ address.is_primary ? lang().label.yes : lang().label.no }}</fwb-table-cell>
                  </fwb-table-row>
                </fwb-table-body>
              </fwb-table>
              <span v-else>No data</span>
            </fwb-tab>
            <fwb-tab name="shop" :title="lang().label.shop">
              <span>No data</span>
            </fwb-tab>
            <fwb-tab name="event" :title="lang().label.event">
              <span>No data</span>
            </fwb-tab>
          </fwb-tabs>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


