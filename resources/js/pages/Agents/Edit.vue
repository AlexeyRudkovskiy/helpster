<script setup>

import Form from './Form.vue'
import {ref, onMounted} from "vue";
import Panel from "@/ui/Panel/Panel.vue";
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const route = useRoute()
const agent = ref(null);

onMounted(() => {
  const id = route.params.id;
  axios.get(`/api/agents/${id}`)
    .then(response => response.data)
    .then(response => agent.value = response);
})
</script>

<template>
<div class="p-8">
  <Panel title="Редагування агента" class="w-96">
    <div>
      <Form :data="agent" :is-edit="true" :id="agent.id" v-if="agent !== null"></Form>
    </div>
  </Panel>
</div>
</template>

<style scoped>

</style>
