<script setup lang="ts">

import {onMounted, ref} from 'vue'
import Sidebar from "@/ui/Sidebar.vue";
import SidebarMenuItem from "@/ui/SidebarMenuItem.vue";
import SidebarMenuGroup from "@/ui/SidebarMenuGroup.vue";
import AppContainer from "@/ui/AppContainer.vue";
import axios from "axios";
import {useRouter} from "vue-router";
import { state as globalState, emit, emitter, user as _user } from "./../global-state.js";

const user = ref({ name: '' });
const router = useRouter();

onMounted(() => {
  axios.get('/api/user')
    .then(response => response.data)
    .then(response => user.value = response)
    .then(() => registerInSocket())
    .then(() => globalState.user = user)
    .then(() => _user.value = user.value)
    .then(() => globalState.user_id = user.value.id)
});

const registerInSocket = () => {
  const { value: authorizedUser } = user;
  if (globalState.socket.readyState === 1) {
    emit('register', {
      id: 'agent_' + authorizedUser.id,
      organization_id: user.value.organization_id
    });
  } else {
    globalState.socket.addEventListener('open', function () {
      emit('register', {
        id: 'agent_' + authorizedUser.id,
        organization_id: user.value.organization_id
      });
    })
  }
}

const navigate = (url) => {
  router.push(url);
}

const logout = () => {
  axios.get('/api/logout')
    .then(response => location.href = '/')
}
</script>

<template>
  <AppContainer>
    <template v-slot:sidebar>
      <Sidebar>
        <SidebarMenuGroup title="Основне">
          <SidebarMenuItem @click="navigate('/home')">Головна</SidebarMenuItem>
          <SidebarMenuItem @click="navigate('/chats')">Повідомлення</SidebarMenuItem>
        </SidebarMenuGroup>

        <SidebarMenuGroup title="Налаштування">
          <SidebarMenuItem @click="navigate('/agents')">Агенти</SidebarMenuItem>
          <SidebarMenuItem @click="navigate('/connection')">Налаштування</SidebarMenuItem>
        </SidebarMenuGroup>
      </Sidebar>
    </template>

    <div class="overflow-hidden dashboard-content">
      <div class="bg-white h-16 w-full shadow-md flex items-center px-8">
        <div class="ml-auto flex items-center">
          <div class="ml-2">
            <div>{{ user.email }}</div>
            <div>
              <div class="inline-block text-sm opacity-85 cursor-pointer hover:text-sky-600" @click="logout">Вийти</div>
            </div>
          </div>
        </div>
      </div>
      <router-view></router-view>
    </div>

  </AppContainer>
</template>

<style scoped>
.dashboard-content {
  max-height: 100vh;
  overflow-y: auto;
}
</style>
