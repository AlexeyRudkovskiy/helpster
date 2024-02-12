<script setup>
import { ref, defineEmits, defineProps } from 'vue'
import Send from "vue-material-design-icons/Send.vue";
import Attachment from "vue-material-design-icons/Attachment.vue";

const emits = defineEmits([ 'send', 'upload' ]);
const props = defineProps({
  placeholder: {
    type: String,
    default: 'Enter new message'
  }
});
const message = ref('');
const inputFile = ref(null);

const sendNewMessage = () => {
  if (message.value.length < 1) {
    return ;
  }

  emits('send', message.value);
  message.value = '';
}

const uploadFile = () => {
  inputFile.value.click()
}

const processSelectedFile = () => {
  const files = inputFile.value.files;
  emits('upload', files)
}
</script>

<template>
  <div class="sticky bottom-0 bg-white border-t">
    <div class="relative">
      <textarea class="p-4 w-full block outline-none resize-none" v-model="message" :placeholder="props.placeholder"></textarea>
      <div class="absolute top-4 right-4 flex">
        <Attachment class="p-2 opacity-75 hover:opacity-100 transition-opacity cursor-pointer" @click="uploadFile"></Attachment>
        <Send class="p-2 opacity-75 hover:opacity-100 transition-opacity cursor-pointer" @click="sendNewMessage"></Send>
        <input type="file" style="display: none" ref="inputFile" @input="processSelectedFile" />
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
