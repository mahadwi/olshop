<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import pkg from "lodash";
import { priceFormat } from "../../helper.js";

import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// import Create from "@/Pages/Event/Create.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Pagination from "@/Components/Pagination.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const { _, debounce, pickBy } = pkg;
const props = defineProps({
  title: String,
  filters: Object,
  products: Object,
  breadcrumbs: Object,
  perPage: Number,
});

const data = reactive({
  params: {
    search: props.filters.search,
    field: props.filters.field,
    order: props.filters.order,
    perPage: props.perPage,
  },
  createOpen: false,
  editOpen: false,
  deleteOpen: false,
  order: null,
  dataSet: usePage().props.app.perpage,
});

const order = (field) => {
  data.params.field = field;
  data.params.order = data.params.order === "asc" ? "desc" : "asc";
};

watch(
  () => _.cloneDeep(data.params),
  debounce(() => {
    let params = pickBy(data.params);
    router.get(route("konsinyasi.index"), params, {
      replace: true,
      preserveState: true,
      preserveScroll: true,
    });
  }, 150)
);
</script>

<template>
  <Head :title="props.title" />
  <AuthenticatedLayout>
    <Breadcrumb :breadcrumbs="breadcrumbs" />

    <div class="flex flex-col mx-auto pb-10">
      <div class="grid mb-10 px-4 pt-6 grid-cols-1 dark:bg-gray-900">
        <div class="col-span-2">
          <div
            class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800"
          >
            <h3 class="mb-4 text-xl font-semibold dark:text-white">
              {{ props.title }}
            </h3>

            <div
              class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700 mb-4"
            >
              <SelectInput
                v-model="data.params.perPage"
                :dataSet="data.dataSet"
              />
              <div class="flex items-center mb-4 sm:mb-0">
                <form class="sm:pr-3" action="#" method="GET">
                  <label for="products-search" class="sr-only">{{
                    lang().placeholder.search
                  }}</label>
                  <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                    <TextInput
                      type="text"
                      v-model="data.params.search"
                      :placeholder="lang().placeholder.search"
                    />
                  </div>
                </form>
              </div>
            </div>

            <div class="overflow-x-auto">
              <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden shadow">
                  <table
                    class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600"
                  >
                    <thead class="bg-gray-100 dark:bg-gray-700">
                      <tr>
                        <th scope="col" class="tbl-head">No</th>
                        <th scope="col" class="tbl-head">
                          {{ lang().label.vendor }}
                        </th>
                        <th scope="col" class="tbl-head">
                          {{ lang().label.product }}
                        </th>
                        <th scope="col" class="tbl-head">
                          {{ lang().label.entry_date }}
                        </th>
                        <th scope="col" class="tbl-head">
                          {{ lang().label.status }}
                        </th>
                        <th scope="col" class="tbl-head">
                          {{ lang().label.action }}
                        </th>
                      </tr>
                    </thead>
                    <tbody
                      class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700"
                    >
                      <tr
                        v-for="(product, index) in products.data"
                        :key="index"
                        class="hover:bg-gray-100 dark:hover:bg-gray-700"
                      >
                        <td class="tbl-column">{{ ++index }}</td>
                        <td class="tbl-column">{{ product.vendor.name }}</td>
                        <td class="tbl-column">{{ product.name }}</td>
                        <td class="tbl-column">{{ product.entry_date }}</td>
                        <td class="tbl-column">{{ product.status }}</td>
                        <td class="p-4 space-x-2 whitespace-nowrap">
                          <Link
                            :href="route('konsinyasi.show', product.id)"
                            class="btn-primary"
                          >
                            <svg
                              class="w-4 h-4 mr-2"
                              fill="currentColor"
                              aria-hidden="true"
                              xmlns="http://www.w3.org/2000/svg"
                              viewBox="0 0 20 20"
                            >
                              <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
                              />
                            </svg>
                            {{ lang().label.detail }}
                          </Link>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div
              class="flex justify-between items-center p-2 border-t border-slate-200 dark:border-slate-700"
            >
              <Pagination :links="props.products" :filters="data.params" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

