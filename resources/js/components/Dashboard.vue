<script>
import {defineComponent} from 'vue'
import SignIn from "./SignIn.vue";
import AskAccess from "./AskAccess.vue";
import Card from "../ui/Card.vue";
import InputField from "../ui/InputField.vue";
import Button from "../ui/Button.vue";
import AppContainer from "../ui/AppContainer.vue";
import Sidebar from "../ui/Sidebar.vue";
import Toolbar from "../ui/Toolbar.vue";
import SidebarMenuGroup from "../ui/SidebarMenuGroup.vue";
import SidebarMenuItem from "../ui/SidebarMenuItem.vue";
import Dropdown from "../ui/Dropdown/Dropdown.vue";
import { Panel } from "../ui/Panel";

import Information from 'vue-material-design-icons/InformationOutline.vue'
import Send from 'vue-material-design-icons/Send.vue'

import { Messages as MessagesContainer } from '@/classes/Messages'
import axios from "axios";

export default defineComponent({
  name: "Dashboard",
  components: {
    Panel,
    Dropdown,
    SidebarMenuItem,
    SidebarMenuGroup,
    Toolbar, Sidebar, AppContainer, InputField, Card, SignIn, AskAccess, Button,
    Information, Send
  },
  data() {
      return {}
  },
  mounted() {

    if (typeof this.$refs.messagesContainer !== "undefined") {
      this.$refs.messagesContainer.style.maxHeight = this.$refs.messagesContainer.offsetHeight + 'px';
      this.$refs.messagesContainer.style.overflowY = 'auto';
    }
  },
  methods: {
    openMessages() {
      this.$router.push('/messanger');
    }
  },
})
</script>

<template>
    <div>Hello world! :-)</div>
</template>

<style scoped>
.full-height {
  height: calc(100%);
}
</style>
