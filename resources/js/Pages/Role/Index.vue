<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import pkg from "lodash";

import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Edit from "@/Pages/Role/Edit.vue";
import Create from "@/Pages/Role/Create.vue";
import Delete from "@/Pages/Role/Delete.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Pagination from "@/Components/Pagination.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";

const { _, debounce, pickBy } = pkg;
const props = defineProps({
    title: String,
    filters: Object,
    roles: Object,
    perPage: Number,
    breadcrumbs:Object,
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
    role: null,
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
        router.get(route("roles.index"), params, {
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

        <Create
            :show="data.createOpen"
            @close="data.createOpen = false"
            :title="props.title"
        />
        <Edit
            :show="data.editOpen"
            @close="data.editOpen = false"
            :role="data.role"
            :title="props.title"
        />
        <Delete
            :show="data.deleteOpen"
            @close="data.deleteOpen = false"
            :role="data.role"
            :title="props.title"
        />

        <div class="flex flex-col mx-auto  pb-10 ">
            <div class="grid mb-10 px-4 pt-6 grid-cols-1 dark:bg-gray-900">
                <div class="col-span-2">
                    <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800"
                    >
                        <h3 class="mb-4 text-xl font-semibold dark:text-white">
                            {{ props.title }}
                        </h3>

                        <button @click="data.createOpen = true"
                        class="btn-primary mb-2" type="button">
                            {{ lang().button.add }}
                        </button>

                        <div class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700 mb-4">
                            <SelectInput
                                v-model="data.params.perPage"
                                :dataSet="data.dataSet"
                            />
                            <div class="flex items-center mb-4 sm:mb-0">
                                <form class="sm:pr-3" action="#" method="GET">
                                    <label for="products-search" class="sr-only">{{ lang().placeholder.search }}</label>
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
                                    <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="tbl-head">
                                                No
                                                </th>
                                                <th scope="col" class="tbl-head">
                                                    {{ lang().label.name }}
                                                </th>
                                                <th scope="col" class="tbl-head">
                                                    {{ lang().label.action }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                            <tr
                                            v-for="(role, index) in roles.data"
                                            :key="index"
                                            class="hover:bg-gray-100 dark:hover:bg-gray-700"
                                        >
                                                <td class="tbl-column"> {{ ++index }}</td>
                                                <td class="tbl-column"> {{ role.name }}</td>
                                                <td class="p-4 space-x-2 whitespace-nowrap">
                                                    <button @click="
                                                                (data.editOpen = true),
                                                                    (data.role = role)
                                                            " type="button" class="btn-primary">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                                        {{ lang().tooltip.edit }}
                                                    </button>
                                                    <button @click="
                                                                (data.deleteOpen = true),
                                                                    (data.role = role)
                                                            " type="button" class="btn-danger">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                        {{ lang().tooltip.delete }}
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center p-2 border-t border-slate-200 dark:border-slate-700">
                            <Pagination :links="props.roles" :filters="data.params" />
                        </div>
                    </div>                   
                </div>
            </div>            
        </div>                
     
    </AuthenticatedLayout>
</template>

