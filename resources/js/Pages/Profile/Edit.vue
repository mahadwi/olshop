<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { FwbInput, FwbTextarea, FwbFileInput } from 'flowbite-vue';
import Multiselect from "@vueform/multiselect";

import { useForm } from "@inertiajs/vue3";
import { watchEffect } from "vue";

const props = defineProps({
    show: Boolean,
    title: String,
    profile: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
    name: "",
    logo: "",
    copyright: "",
    subdistrict_id: "",
});

const update = () => {
    form.post(route("profile.update", props.profile?.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit("close");
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
};

watchEffect(() => {
    if (props.show) {
        form.name = props.profile?.name;
        form.copyright = props.profile?.copyright;
        form.subdistrict_id = {value:props.profile?.subdistrict_id, label:`${props.profile.subdistrict.city.province.name}, ${props.profile.subdistrict.city.name}, ${props.profile.subdistrict.name}`};
        form.errors = {};
    }
});

const fetchOptions = async (query) => {    

    const response = await fetch(
        'get-kecamatan?query=' + query
    );

    const data = await response.json(); // Here you have the data that you need

    return data.map((item) => {
        return { value: item.id, label: item.fullname }
    })

}


</script>

<template>
    <section class="space-y-6">
        <Modal :show="props.show" @close="emit('close')">
            <form class="p-6" @submit.prevent="update">
                <h2
                    class="text-lg font-medium text-slate-900 dark:text-slate-100"
                >
                    {{ lang().label.edit }} {{ props.title }}
                </h2>
                <div class="my-6 space-y-4">

                    <fwb-input v-model="form.name" :placeholder="lang().placeholder.name" :label="lang().placeholder.name" />
                    <InputError class="mt-2" :message="form.errors.name" />

                </div>
                <div class="my-6 space-y-4">
                    <FwbInput v-model="form.copyright" :placeholder="lang().label.copyright" :label="lang().label.copyright" />
                    <InputError class="mt-2" :message="form.errors.copyright" />
                </div>
                <div class="my-6 space-y-4">
                    <InputLabel for="subdistrict" :value="lang().label.subdistrict" />

                    <Multiselect
                        v-model="form.subdistrict_id"
                        placeholder="Select"
                        :filter-results="false"
                        :min-chars="3"
                        :resolve-on-load="false"
                        :object="true"
                        :delay="1"
                        :searchable="true"
                        :options="async function(query) {
                            return await fetchOptions(query) // check JS block for implementation
                        }"
                    />

                    <InputError class="mt-2" :message="form.errors.subdistrict_id" />
                </div>
                <div class="my-6 space-y-4">
                    <FwbFileInput accept="image/*" v-model="form.logo" :label="lang().label.logo" />

                    <InputError class="mt-2" :message="form.errors.logo" />
                </div>
                <div class="flex justify-end">
                    <SecondaryButton
                        :disabled="form.processing"
                        @click="emit('close')"
                    >
                        {{ lang().button.close }}
                    </SecondaryButton>
                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="update"
                    >
                        {{
                            form.processing
                                ? lang().button.save + "..."
                                : lang().button.save
                        }}
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    </section>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
