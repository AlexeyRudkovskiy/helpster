<script setup lang="ts">

import {ref, onMounted} from "vue";
import { useRouter } from 'vue-router'

import Panel from "@/ui/Panel/Panel.vue";
import Table from "@/ui/Table/Table.vue";
import Button from "@/ui/Button.vue";
import MoonLoader from 'vue-spinner/src/MoonLoader.vue'
import axios from "axios";
import Pagination from "@/ui/Pagination.vue";

const router = useRouter();
const agents = ref(null);
const total = ref({});
const lastPage = ref(0);
const page = ref(0);

const nextPage = ref('');
const previousPage = ref('');
const currentPage = ref('');

const isLoading = ref(false)
const columns = ref([
  { key: 'name', label: 'ФІО', classes: ['w-20'] },
  { key: 'email', label: 'E-Mail', classes: [] },
  { key: 'id', label: 'ID', classes: ['w-8'] }
]);

onMounted(() => {
  loadResults('/api/agents')
})

const addNewAgent = () => {
  router.push('/agents/create');
}

const editAgent = (item) => {
  router.push(`/agents/${item.id}`)
}

const deleteAgent = (agent) => {
  if (confirm('Ви дійсно хочете видалити цього агента?')) {
    isLoading.value = true;

    axios.delete(`/api/agents/${agent.id}`)
      .then(() => loadResults(currentPage.value))
  }
}

const loadNextPage = () => {
  loadResults(nextPage.value)
}

const loadPreviousPage = () => {
  loadResults(previousPage.value)
}

const loadResults = (url) => {
  isLoading.value = true;
  currentPage.value = url;

  axios.get(url)
    .then(response => response.data)
    .then(response => {
      agents.value = response.data
      total.value = response.total

      nextPage.value = response.next_page_url
      previousPage.value = response.prev_page_url

      lastPage.value = response.last_page
      page.value = response.current_page
    })
    .finally(() => isLoading.value = false)
}
</script>

<template>
<div class="p-8">
  <Panel title="Агенти" v-if="agents !== null" :no-body-paddings="true" :is-table-content="true">
    <div v-if="isLoading" class="flex p-8 justify-center">
      <MoonLoader :size="'48px'" class="spinner" color="#34495e"></MoonLoader>
    </div>
    <Table :items="agents" :columns="columns" v-if="!isLoading">
      <template v-slot:header_id="column">
        <div class="text-right">
          <Button size="small" @click="addNewAgent" class="inline-block">Додати</Button>
        </div>
      </template>

      <template v-slot:id="item">
        <Button size="small" class="inline-block mr-1" @click="editAgent(item)">Редагувати</Button>
        <Button size="small" class="inline-block" @click="deleteAgent(item)" :danger="true">Видалити</Button>
      </template>
    </Table>
    <div class="p-4 pb-6 flex" v-if="!isLoading">
      <Pagination class="ml-auto" :totalPages="lastPage" :page="page" @next="loadNextPage" @previous="loadPreviousPage"></Pagination>
    </div>
  </Panel>
</div>
</template>

<style scoped>

</style>
