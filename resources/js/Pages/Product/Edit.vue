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
    Textarea, FileInput, Input
} from 'flowbite-vue'

const props = defineProps({
    show: Boolean,
    title: String,
    product: Object,
    categories: Object,
    vendors: Object,
    brands: Object,
    colors: Object,
    breadcrumbs:Object,
    commissionType:Object,
});

const form = useForm({
    name: props.product.name,
    brand_id: props.product.brand_id,
    description: props.product.description,
    product_category_id: props.product.product_category_id,
    user_id: props.product.user_id,
    stock: props.product.stock,
    image: "",
    price: props.product.price,
    sale_price: props.product.sale_price,
    commission: props.product.commission,
    commission_type: props.product.commission_type,
    display_on_homepage: props.product.display_on_homepage,
    history: props.product.history,
    entry_date: props.product.entry_date,
    expired_date: props.product.expired_date,
    description: props.product.description,
    color_id: props.product.color_id,
});

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
    value: data
}))

const dataSwitch = [
	{
		label: 'Yes',
		value: true
	},
	{
		label: 'No',
		value: false
	}
]

const formatter = ref({
  date: 'DD-MM-YYYY',
  month: 'MMM',
})

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
	
	if(form.commission_type == 'Selling'){
			form.commission = 0;

			if(form.sale_price == 0) form.sale_price = form.price; 
	}
}

</script>

<template>
    <Head :title="props.title" />
    <AuthenticatedLayout>
    	<Breadcrumb :breadcrumbs="breadcrumbs" />
  
      <div class="grid grid-cols-1 mb-10 px-4 pt-6 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
          <div class="col-span-2">
              <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800"
              >
                  <h3 class="mb-4 text-xl font-semibold dark:text-white">
                      {{ props.title }}
                  </h3>
                  <form @submit.prevent="update">
                      <div class="grid grid-cols-6 gap-6">
                          <div class="col-span-6 sm:col-span-3">                           
                            	<Input v-model="form.name" :placeholder="lang().placeholder.name" :label="lang().placeholder.name" />
                              <InputError class="mt-2" :message="form.errors.name" />
                          </div>
                          <div class="col-span-6 sm:col-span-3">
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
  
                              <Textarea rows="4" :placeholder="lang().label.description" v-model="form.description" :label="lang().label.description" />
                              <InputError class="mt-2" :message="form.errors.description" />
  
                          </div>
                          <div class="col-span-6 sm:col-span-3">
                              <InputLabel for="entry_date" :value="lang().label.entry_date" />
                              <vue-tailwind-datepicker v-model="form.entry_date" :formatter="formatter" :placeholder="lang().label.entry_date"  as-single />
                              <InputError class="mt-2" :message="form.errors.entry_date" />
                          </div>
                          <div class="col-span-6 sm:col-span-3">
                              <InputLabel for="expired_date" :value="lang().label.expired_date" />
                              <vue-tailwind-datepicker v-model="form.expired_date" :formatter="formatter" :placeholder="lang().label.expired_date"  as-single />
                              <InputError class="mt-2" :message="form.errors.expired_date" />
                          </div>
                          <div class="col-span-6 sm:col-span-3">
                              <InputLabel for="product_category" :value="lang().label.product_category" />
                              <SelectInput
                                  id="product_category"
                                  class="mt-1 block w-full"
                                  v-model="form.product_category_id"
                                  :dataSet="categories"
                              >
                              </SelectInput>
                              <InputError class="mt-2" :message="form.errors.product_category_id" />
                          </div>
                         
                          <div class="col-span-6 sm:col-span-3">
                              <InputLabel for="vendor" :value="lang().label.vendor" />
                              <SelectInput
                                  id="vendor"
                                  class="mt-1 block w-full"
                                  v-model="form.user_id"
                                  :dataSet="vendors"
                              >
                              </SelectInput>
                              <InputError class="mt-2" :message="form.errors.user_id" />
                          </div>                                        
  
                          <div class="col-span-6 sm:col-span-3">

					           					<Input v-model="form.stock" :placeholder="lang().label.stock" :label="lang().label.stock" />
                              <InputError class="mt-2" :message="form.errors.stock" />
                          </div>
  
                          <div class="col-span-6 sm:col-span-3">
															<Input v-model="form.price" :placeholder="lang().label.price" :label="lang().label.price" />
                              <InputError class="mt-2" :message="form.errors.price" />
                          </div>
													<div class="col-span-6 sm:col-span-3">
                              <InputLabel for="commission_type" :value="lang().label.commission_type" />
                              <SelectInput
                                  id="commission_type"
                                  class="mt-1 block w-full"
                                  v-model="form.commission_type"
                                  :dataSet="commissionType"
																	@change="changeCommission()"
                              >
                              </SelectInput>
                              <InputError class="mt-2" :message="form.errors.commission_type" />
                          </div>
													<div class="col-span-6 sm:col-span-3">
															<Input :disabled="form.commission_type == 'Percent'" v-model="form.sale_price" :placeholder="lang().label.sale_price" :label="lang().label.sale_price" />
                              <InputError class="mt-2" :message="form.errors.sale_price" />
                          </div>
													<div class="col-span-6 sm:col-span-3">
															<Input :disabled="form.commission_type == 'Selling'" v-model="form.commission" :placeholder="lang().label.commission" :label="lang().label.commission" />
                              <InputError class="mt-2" :message="form.errors.commission" />
                          </div>
													<div class="col-span-6 sm:col-span-3">
                              <InputLabel for="display_on_homepage" :value="lang().label.display_on_homepage" />
                              <SelectInput
                                  id="display_on_homepage"
                                  class="mt-1 block w-full"
                                  v-model="form.display_on_homepage"
                                  :dataSet="dataSwitch"
                              >
                              </SelectInput>
                              <InputError class="mt-2" :message="form.errors.display_on_homepage" />
                          </div>
													<div class="col-span-6 sm:col-span-3">
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
  
                              <Textarea rows="4" :placeholder="lang().label.history" v-model="form.history" :label="lang().label.history" />
                              <InputError class="mt-2" :message="form.errors.history" />
                              
                          </div>
												
                          <div class="col-span-6 sm:col-span-3">
                              <FileInput accept="image/*" v-model="form.image" :label="lang().label.image" />
  
                              <InputError class="mt-2" :message="form.errors.image" />
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


