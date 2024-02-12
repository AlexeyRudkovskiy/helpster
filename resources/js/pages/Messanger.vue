<script setup lang="ts">

import {ref} from 'vue';
import Card from "@/ui/Card.vue";
import InputField from "@/ui/InputField.vue";
import Information from "vue-material-design-icons/InformationOutline.vue";
import { Messages, Message, MessageGroup, Compose, KeepScroll } from '@/ui/Messages'
import { List } from '@/ui/List'


const messages = ref([]);
const tableData = ref([]);
const selectedChat = ref(null);
const showInfo = ref(false);

const setSelected = (chat) => console.log(chat)
const pushMessage = () => console.log('push');
</script>

<template>
    <div class="p-8 full-height flex">
        <Card class="overflow-hidden w-full flex justify-stretch" title="Messages" :no-content-styles="true" :no-header="true" :no-footer="true">
            <div class="flex w-full items-stretch">
                <div class="w-96 border-r shrink-0">
                    <List
                        :data="tableData"
                        :clickable="true"
                        :selectable="true"
                        :selected="selectedChat"
                        @clicked="setSelected"
                        title="username"
                        subtitle="email"
                        image="avatar"
                    ></List>
                </div>
                <div class="w-full bg-slate-50 h-full" ref="messagesContainer">
                    <Messages v-if="selectedChat !== null" class="relative">
                        <div class="p-4 bg-slate-200 flex items-start sticky top-0">
                            <img :src="selectedChat.avatar" :alt="selectedChat.username" class="w-16 h-16 rounded">
                            <div class="ml-4">
                                <div class="text-lg">{{ selectedChat.username }}</div>
                                <div class="opacity-75">{{ selectedChat.email }}</div>
                            </div>
                            <div class="ml-auto self-center">
                                <Information :size="32" class="opacity-25 hover:opacity-50 transition-opacity cursor-pointer" @click="showInfo = !showInfo"></Information>
                            </div>
                        </div>
                        <KeepScroll class="p-4" :id="selectedChat.id" :container="$refs.messagesContainer">
                            <MessageGroup v-for="group in messages">
                                <Message :my-message="message.sender === user.id" v-for="message in group.messages">{{ message.message }}</Message>
                            </MessageGroup>
                        </KeepScroll>
                        <Compose @send="pushMessage"></Compose>
                    </Messages>
                </div>
                <div class="w-96 bg-red-50 shrink-0" v-if="showInfo">

                </div>
            </div>
        </Card>
    </div>
</template>

<style scoped>

</style>
