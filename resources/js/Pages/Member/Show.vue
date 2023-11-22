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
	FwbInput,
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
                v-model="member.name"
                :placeholder="lang().placeholder.name"
                :label="lang().placeholder.name"
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

          <!-- <tabs variant="underline" v-model="activeTab" class="p-5">
            <tab name="address" :title="lang().label.address">
							<div class="grid grid-cols-12">							
								<table>
									<table-head>
										<table-head-cell>Product name</table-head-cell>
										<table-head-cell>Color</table-head-cell>
										<table-head-cell>Category</table-head-cell>
										<table-head-cell>Price</table-head-cell>
										<table-head-cell>
											<span class="sr-only">Edit</span>
										</table-head-cell>
									</table-head>
								</table>
							</div>
            </tab>
            <tab name="shop" :title="lang().label.shop">
              Donec eleifend dapibus turpis, vitae porta odio dictum sed.
            </tab>
            <tab name="event" :title="lang().label.event">
              Class aptent taciti sociosqu ad litora torquent per conubia
              nostra, per inceptos himenaeos.
            </tab>
          </tabs> -->
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


