<script setup lang="ts">

import {ref, onMounted} from "vue";
import Panel from "@/ui/Panel/Panel.vue";
import Table from "@/ui/Table/Table.vue";
import axios from "axios";


const overview = ref({
  agents: 0,
  agents_online: 0,
  tickets: 0
});

onMounted(() => {
  axios.get('/api/overview')
    .then(response => response.data)
    .then(response => overview.value = response)
});
</script>

<template>
  <div class="p-8 full-height">
    <div class="mb-8 grid gap-4 grid-cols-6">
      <div class="p-8 rounded-xl shadow bg-white">
        <div class="text-4xl">{{ overview.agents }}</div>
        <div class="text-xl text-slate-500">Агентів</div>
      </div>
      <div class="p-8 rounded-xl shadow bg-white">
        <div class="text-4xl">{{ overview.agents_online }}</div>
        <div class="text-xl text-slate-500">Агентів онлайн</div>
      </div>
      <div class="p-8 rounded-xl shadow bg-white">
        <div class="text-4xl">{{ overview.tickets }}</div>
        <div class="text-xl text-slate-500">Відкритих тікітів</div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
