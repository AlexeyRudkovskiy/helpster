<script setup>

import Form from './Form.vue'
import {ref} from "vue";
import Panel from "@/ui/Panel/Panel.vue";
import {useRouter} from "vue-router";

const router = useRouter();
const agent = ref({
  name: '',
  email: '',
  password: '',
  re_password: ''
});
</script>

<template>
<div class="p-8">
  <Panel title="Додавання нового агента" class="w-96">
    <div>
      <Form :data="agent"></Form>
    </div>
  </Panel>
</div>
</template>

<style scoped>

</style>
