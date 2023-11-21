<script setup>
    
    import { onMounted, ref } from "vue";

    defineProps({
        modelValue: String,       
    });

    defineEmits(["update:modelValue"]);

    const input = ref(null);

    defineExpose({ focus: () => input.value.focus() });

</script>

<template>
    <vue-tailwind-datepicker as-single :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        ref="input" />
</template>
