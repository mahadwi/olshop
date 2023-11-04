<script setup>
import { onMounted } from "vue";
import { create, registerPlugin } from "filepond";
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

const props = defineProps({
  id: {
    type: String,
    default: 'filepod_' + Math.floor(Math.random() * 100) + 1
  },
  labelIdle: {
    default: 'Upload Images...',
    type: String
  }
})

const emit = defineEmits(['update:modelValue'])

registerPlugin(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview,
  FilePondPluginImageExifOrientation
)

onMounted(() => {
  const fileInput = document.querySelector('#' + props.id);

  const pond = create(fileInput, {
    storeAsFile: true,
    dropOnPage: true,
    allowMultiple: true,
    credits: null,
    labelIdle: props.labelIdle,
    labelFileTypeNotAllowed: 'File Type Not Allowed',
    maxFileSize: "1MB",
    labelMaxFileSize: "Maximum file size is 1MB"
  });

  pond.on('updatefiles', (files) => {
    emit(
      'update:modelValue',
      files.map(function (filepond) {
        return filepond.file
      })
    )
  });
})
</script>

<template>
  <input :id="id" type="file">
</template>

<style scoped>

</style>