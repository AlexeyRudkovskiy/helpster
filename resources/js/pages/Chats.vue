<script setup lang="ts">

import {ref, computed, getCurrentInstance, nextTick} from 'vue';
import Card from "@/ui/Card.vue";
import Information from "vue-material-design-icons/InformationOutline.vue";
import FaceAgent from "vue-material-design-icons/FaceAgent.vue";
import Person from "vue-material-design-icons/Account.vue";
import { Messages, Message, MessageGroup, Compose, KeepScroll } from '@/ui/Messages'
import { Messages as MessagesContainer } from '@/classes/Messages'
import InputField from "@/ui/InputField.vue";
import Button from "@/ui/Button.vue";
import { List } from '@/ui/List'
import axios from "axios";
import { state as globalState, emit, emitter, user as _user } from "./../global-state.js";
import Attachment from "vue-material-design-icons/Attachment.vue";
import Delete from "vue-material-design-icons/Delete.vue";


const container = new MessagesContainer([]);

const messages = ref([]);
const tableData = ref([]);
const selectedChat = ref(null);
const activeChat = ref(null)
const showInfo = ref(false);
const groups = ref([])
const chats = ref([]);
const details = ref(null);
const files = ref([]);

const customerName = ref('');
const customerNameError = ref(null);

const messagesContainer = ref(null);
container.on('push', function () {
  groups.value = container.getGroups();
});

const sortedChats = computed(() => {
  return chats.value.sort((a, b) => {
    const x = a.updated_timestamp;
    const y = b.updated_timestamp;

    if (x === null && y !== null) {
      return -1; // Переместить a в конец списка
    } else if (x !== null && y === null) {
      return 1; // Переместить b в конец списка
    } else if (x === null && y === null) {
      return 0; // Считаем их равными, порядок не важен
    } else {
      // Сортировка по возрастанию
      return y - x;
    }

  });
})

emitter.on('socket:event', function (event, payload) {
  if (event !== 'message') {
    return ;
  }

  const message = payload.payload;

  if (message.user_id === globalState.user_id) {
    return ;
  }

  if (selectedChat.value !== null && selectedChat.value.id === message.chat_id) {
    container.pushMessage(message, true);
    selectedChat.value.last_message = message;
    selectedChat.value.updated_timestamp = message.chat.updated_timestamp;

    axios.get(`/api/chats/${selectedChat.value.id}/status`);
  }
});

emitter.on('socket:event', function (event, payload) {
  if (event !== 'update') {
    return;
  }

  const message = payload;

  if (selectedChat.value !== null && message.chat_id === selectedChat.value.id) {
    return ;
  }

  if (payload.user_id === globalState.user_id) {
    return ;
  }

  const chat = chats.value.find(chat => chat.id === message.chat_id);
  if (typeof chat !== "undefined") {
    chat.read = 0;
    if (chat.last_message === null) {
      chat.last_message = { message: '' };
    }
    chat.last_message.message = payload.message;
    chat.updated_at = message.chat.updated_at;
    chat.updated_timestamp = message.chat.updated_timestamp;
  }
});

const setSelected = (chat, manual = false) => {
  if (selectedChat.value !== null) {
    emit('left', { chatId: selectedChat.value.id })
  }

  if (!manual) {
    details.value = null;
  }

  axios.get(`/api/chats/${chat.id}`)
    .then(response => response.data)
    .then(response => {
      chat.read = true;
      selectedChat.value = chat
      activeChat.value = response
      container.init(response.messages)
      groups.value = container.getGroups()

      customerName.value = response.customer.full_name;

      emit('join', { chatId: response.id })

      setTimeout(() => {
        const scrollHeight = messagesContainer.value.scrollHeight + 100;
        messagesContainer.value.scrollTo(0, scrollHeight);
      }, 25);

      if (details.value !== null) {
        showDetails()
      }
    })
}
const pushMessage = (message) => {
  axios.put(`/api/chats/${selectedChat.value.id}`, { message, files: files.value })
    .then(response => response.data)
    .then(response => {
      container.pushMessage(response, true);
      files.value = null;

      setTimeout(() => {
        const scrollHeight = messagesContainer.value.scrollHeight + 100;
        messagesContainer.value.scrollTo(0, scrollHeight);
      }, 25);

      chats.value
        .filter(chat => chat.id === selectedChat.value.id)
        .forEach(chat => {
          chat.last_message = response;
          chat.updated_timestamp = response.chat.updated_timestamp
        })
    })
};

const showDetails = () => {
  axios.get(`/api/chats/${selectedChat.value.id}/details`)
    .then(response => response.data)
    .then(response => details.value = response)
}

const toggleDetails = () => {
  if (details.value !== null) {
    details.value = null;
  } else {
    showDetails();
  }
}

const showChat = (showChat) => {
  const chat = chats.value.find(_chat => _chat.id === showChat.id);
  if (typeof chat !== "undefined") {
    setSelected(chat, true);
  }
}

const updateCustomer = () => {
  axios.post(`/api/chats/${selectedChat.value.id}/customer`, {
    name: customerName.value
  })
    .then(response => response.data)
    .then(response => {
      chats.value.forEach(chat => {
        if (chat.customer_id === selectedChat.value.customer_id) {
          chat.customer_name = customerName.value
        }
      });
    })
    .then(() => alert('Клієнта успішно оновлено'))
}

axios.get('/api/chats')
  .then(response => response.data)
  .then(response => chats.value = response)

const uploadFile = (selectedFiles) => {
  axios.post(`/api/chats/${selectedChat.value.id}/attachment`, {
    files: selectedFiles
  }, {
    onUploadProgress: progress => console.log(progress),
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
    .then(response => response.data)
    .then(response => {
      files.value = response
    });
}

const removeFile = (index) => {
  files.value.splice(index, 1);
}
</script>

<template>
  <div class="p-8">
    <Card class="overflow-hidden w-full flex justify-stretch" title="Messages" :no-content-styles="true" :no-header="true" :no-footer="true">
      <div class="flex w-full items-stretch">
        <div class="w-96 border-r shrink-0 full-height">
          <List
            :data="sortedChats"
            :clickable="true"
            :selectable="true"
            :selected="selectedChat"
            @clicked="setSelected"
            title="customer_name"
            subtitle="last_message.message"
            :show-unread="true"
          >
            <template v-slot:empty>
              <div class="p-8 mt-6 text-center text-gray-500">Ще не має жодного відкритого чату</div>
            </template>
          </List>
        </div>
        <div class="w-full bg-slate-50 full-height" ref="messagesContainer">
          <Messages v-if="selectedChat !== null" class="relative">
            <div class="min-full-height">
              <div class="p-4 bg-slate-200 flex items-start sticky top-0">
                <div class="ml-4">
                  <div class="text-lg">{{ activeChat.customer.full_name }}</div>
                </div>
                <div class="ml-auto self-center">
                  <Information :size="32" class="opacity-25 hover:opacity-50 transition-opacity cursor-pointer" @click="toggleDetails"></Information>
                </div>
              </div>
              <KeepScroll class="p-4" :id="selectedChat.id" :container="$refs.messagesContainer">
                <MessageGroup v-for="group in groups" :key="group.id" class="mt-2 first:mt-0">
                  <Message :my-message="message.user_id !== null" v-for="message in group.messages" :key="message.id">
                    {{ message.message }}
                    <div v-if="typeof message.files !== 'undefined' && message.files.length > 0" class="mt-4">
                      <a v-for="file in message.files" :href="file.path" target="_blank" class="flex text-sm items-center">
                        <Attachment class="opacity-50 mr-2"></Attachment>
                        {{ file.filename }}
                      </a>
                    </div>
                  </Message>
                </MessageGroup>
              </KeepScroll>
            </div>
            <div v-if="files !== null && files.length > 0" class="sticky bottom-0 p-4 mb-0 mt-0">
              <div v-for="(file, index) in files" class="flex">
                <Attachment class="opacity-50 mr-2"></Attachment>
                {{ file.name }}
                <Delete class="opacity-50 cursor-pointer ml-2" @click="removeFile(index)"></Delete>
              </div>
            </div>
            <Compose class="mt-0" @send="pushMessage" @upload="uploadFile"></Compose>
          </Messages>
        </div>
        <div class="full-height w-96 bg-white shrink-0 p-8 border-l" v-if="details !== null">
          <h1 class="text-xl mb-2">Відомості про клієнта</h1>
          <div class="mb-10">
            <InputField label="ФІО" v-model="customerName" :error="customerNameError !== null" :error-message="customerNameError" />
            <Button @click="updateCustomer">Зберігти</Button>
          </div>

          <template v-if="details.files.length > 0">
            <h1 class="text-xl mb-2">Надіслані файли</h1>
            <div class="mb-10 space-y-2">
              <div v-for="file in details.files">
                <a :href="file.path" target="_blank" class="inline-flex underline hover:text-sky-600">
                  <Attachment class="opacity-50 mr-2"></Attachment>
                  {{ file.filename }}
                </a>
              </div>
            </div>
          </template>

          <h1 class="text-xl mb-2">Інші чати</h1>
          <div class="space-y-2 mb-6">
            <div class="rounded rounded-xl overflow-hidden shadow p-4 cursor-pointer hover:bg-gray-50" v-for="chat in details.chats" @click="showChat(chat)">
              <div class="mb-4" v-if="chat.first_message !== null">{{ chat.first_message.message }}</div>
              <div class="flex" v-if="chat.user !== null"><FaceAgent class="opacity-50 mr-2" />{{ chat.user.name }}</div>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </div>
</template>

<style scoped>
.full-height {
  height: calc(100vh - 8rem);
  overflow-y: auto;
}
.min-full-height {
  min-height: calc(100vh - 13rem);
}
</style>
