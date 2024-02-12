<script setup>
import sendIcon from './send-icon.svg'
import {nextTick, ref} from "vue";
import axios from "axios";
import { Messages } from '../classes/Messages'
import { Message, MessageGroup } from '../ui/Messages'
import { state as globalState, emitter, emit } from "../global-state.js";
import Attachment from "vue-material-design-icons/Attachment.vue";
import CommentQuestion from "vue-material-design-icons/CommentQuestion.vue";
import WindowMinimize from "vue-material-design-icons/WindowMinimize.vue";


const chatIdFromStorage = localStorage.getItem('@chat_id');
const messagesFromStorage = localStorage.getItem("@messages");
let messagesArray = messagesFromStorage !== null ? JSON.parse(messagesFromStorage) : [];
const container = new Messages(messagesArray);

const messages = ref([]);
const message = ref('');
const groups = ref(container.getGroups());
const chat = ref(chatIdFromStorage);
const customer = ref(localStorage.getItem('@customer'))
const widgetState = ref(false);
const unreadMessages = ref(0);
const messagesContainer = ref(null);

const scrollToBottom = () => {
  setTimeout(() => {
    if (messagesContainer.value === null) {
      return;
    }

    const scrollHeight = messagesContainer.value.scrollHeight + 100;
    messagesContainer.value.scrollTo(0, scrollHeight);
  }, 10);
}

if (chat.value !== null) {
  emit('register', {
    id: customer.value
  });

  setTimeout(() => {
    emit('join', { chatId: chat.value })
  }, 100)

  axios.get(`https://ws-backend.test/api/widget/chat/${chat.value}?app_id=${window.helpster.app_id}`)
    .then(response => response.data)
    .then(response => {
      messagesArray = response
      container.init(messagesArray)
      groups.value = container.getGroups()

      scrollToBottom();
    })
}

emitter.on('socket:event', function (event, payload) {
  if (event !== 'message') {
    return ;
  }

  if (payload.payload.customer_id !== null) {
    return ;
  }

  container.pushMessage(payload.payload, true);
  nextTick(() => {
    groups.value = container.getGroups();
    messagesArray.push(payload.payload);
    localStorage.setItem('@messages', JSON.stringify(messagesArray));

    if (widgetState.value === false) {
      unreadMessages.value = unreadMessages.value + 1;
    }
  });
});

const sendMessage = () => {
  if (message.value.length < 5) {
    return ;
  }

  if (chat.value === null) {
    /// Create chat
    axios.post('https://ws-backend.test/api/widget/chat', {
      ...window.helpster.params,
      app_id: window.helpster.app_id,
      message: message.value
    })
      .then(response => response.data)
      .then(response => {
        chat.value = response.id
        const message = response.message
        localStorage.setItem('@chat_id', response.id);
        container.pushMessage(message);
        messagesArray.push(message)
        localStorage.setItem('@messages', JSON.stringify(messagesArray))

        // Customer
        localStorage.setItem('@customer', 'customer_' + response.customer.id);
        customer.value = response.customer.id

        emit('register', {
          id: 'customer_' + customer.value
        });

        setTimeout(() => {
          emit('join', { chatId: response.id })
        }, 100)
      })
      .then(() => message.value = '');
  } else {
    axios.post(`https://ws-backend.test/api/widget/chat/${chat.value}`, {
      message: message.value
    })
      .then(response => response.data)
      .then(response => {
        container.pushMessage(response);
        messagesArray.push(response)
        localStorage.setItem('@messages', JSON.stringify(messagesArray))
        message.value = ''

        scrollToBottom();
      });
  }
}

const toggleWidget = () => {
  widgetState.value = !widgetState.value;
  scrollToBottom()
}
</script>

<template>
<div class="fixed bottom-4 right-4">
  <div v-if="!widgetState" class="w-16 h-16 flex items-center justify-center rounded-full bg-sky-500 shadow cursor-pointer relative" @click="toggleWidget">
    <CommentQuestion size="32" class="text-white"></CommentQuestion>
    <span v-if="unreadMessages > 0" class="absolute inline-block -top-2 -right-2 text-sm bg-red-500 px-3 py-1 rounded-full text-white">{{ unreadMessages }}</span>
  </div>
  <div class="chat-container w-96 shadow-xl rounded-xl overflow-hidden" v-if="widgetState">
    <div class="p-4 py-5 bg-sky-500 text-white font-bold rounded-t-xl relative">
      <div>Підтримка користувачів</div>
      <WindowMinimize class="absolute top-3 right-3 p-2 rounded rounded-lg hover:bg-sky-400 cursor-pointer" @click="toggleWidget"></WindowMinimize>
    </div>
    <div class="p-4 overflow-y-auto" style="max-height: 50vh; min-height: 200px;" ref="messagesContainer">
      <MessageGroup v-for="group in groups" :key="group.id" class="mt-2 first:mt-0">
        <Message :my-message="message.customer_id !== null" v-for="message in group.messages" :key="message.id">
          {{ message.message }}
          <div v-if="typeof message.files !== 'undefined' && message.files.length > 0" class="mt-4">
            <a v-for="file in message.files" :href="file.path" target="_blank" class="flex text-sm items-center">
              <Attachment class="opacity-50 mr-2"></Attachment>
              {{ file.filename }}
            </a>
          </div>
        </Message>
      </MessageGroup>
    </div>
    <div class="relative">
      <textarea class="p-4 w-full outline-none rounded-b-xl block resize-none h-24" placeholder="Напишіть ваше повідомлення" v-model="message"></textarea>
      <img :src="sendIcon" class="absolute top-4 right-4 text-sky-600 w-10 h-10 p-2 rounded hover:bg-slate-50 cursor-pointer" @click="sendMessage" />
    </div>
  </div>
</div>
</template>

<style scoped>

</style>
