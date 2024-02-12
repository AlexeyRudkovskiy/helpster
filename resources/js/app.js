import './bootstrap';

import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { state as globalState, emitter } from "./global-state.js";

import App from './App.vue'

// Router
import Messanger from "./pages/Messanger.vue";
import Home from "./pages/Home.vue";
import DashboardLayout from "./layouts/DashboardLayout.vue";
import Login from "./pages/Login.vue";
import { Index as AgentsIndex, Create as AgentsCreate, Edit as AgentsEdit } from './pages/Agents'
import Chats from "./pages/Chats.vue";
import Register from "./pages/Register.vue";
import Connection from "./pages/Connection.vue";

const routes = [
  {
    path: '/',
    component: DashboardLayout,
    children: [
      { path: '/home', component: Home },
      { path: '/chats', component: Chats },
      { path: '/agents', component: AgentsIndex },
      { path: '/agents/create', component: AgentsCreate },
      { path: '/agents/:id', component: AgentsEdit },
      { path: '/connection', component: Connection },
      { path: '/', component: Home },
    ]
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/register',
    component: Register
  }
]

const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes
})


const app = createApp(App);
app.use(router);
app.mount('#app')


const socket = new WebSocket('wss://142.93.168.9:8080');

socket.addEventListener('message', (e) => {
  let data = e.data;
  data = JSON.parse(data);
  const { event, payload } = data;

  emitter.emit('socket:event', event, payload);
});


globalState.socket = socket;
