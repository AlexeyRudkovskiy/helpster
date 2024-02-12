<script>
import {defineComponent} from 'vue'
import InputField from "../ui/InputField.vue";
import Button from "../ui/Button.vue";
import GoogleSignIn from "./GoogleSignIn.vue";
import AccountIcon from 'vue-material-design-icons/Account.vue'
import AppsIcon from 'vue-material-design-icons/Apps.vue'
import CookieIcon from 'vue-material-design-icons/Cookie.vue'
import List from "../ui/List.vue";
import ListItem from "../ui/ListItem.vue";
import Card from "../ui/Card.vue";

export default defineComponent({
    name: "AskAccess",
    components: {ListItem, List, GoogleSignIn, Button, InputField, AccountIcon, AppsIcon, CookieIcon, Card},
    data() {
        return {
            allowLoading: false,
            logo: 'https://uxwing.com/wp-content/themes/uxwing/download/brands-and-social-media/twitter-app-icon.png',

            permissions: [
                {
                    key: 'account',
                    icon: 'AccountIcon',
                    title: 'Personal Information',
                    description: 'Give access to your first and last name, birthday and contact information'
                },
                {
                    key: 'apps',
                    icon: 'AppsIcon',
                    title: 'Apps',
                    description: 'Give access to your apps and their settings'
                },
                {
                    key: 'sessions',
                    icon: 'CookieIcon',
                    title: 'Sessions',
                    description: 'Manage active sessions'
                }
            ]
        };
    },
    methods: {
        allowAccess() {
            this.allowLoading = true;
            const { scope, clientId, redirectUri, state } = window.authorize_reuqest;

            axios.post('/api/issue-code', { scope, state, redirect_uri: redirectUri, client_id: clientId })
                .then(response => response.data)
                .then(response => location.href = response.redirect)
                .finally(() => this.allowLoading = false);
        }
    },
    computed: {
        requestedPermissions() {
            const scope = window.authorize_reuqest.scope
            return this.permissions.filter(permission => scope.indexOf(permission.key) > -1);
        }
    },
    mounted() {
        axios.get('/api/user')
            .then(response => response.data)
            .then(response => console.log(response));
    }
})
</script>

<template>
    <Card :no-content-styles="true" :no-header="true">
        <div class="p-6 pb-2">
            <div class="flex flex-col justify-center items-center">
                <img :src="logo" class="rounded-xl w-16 mb-2" />
                <span class="text-xl font-medium">Twitter</span>
                <span class="mt-4 mb-4 text-center">The app is asking for access to your account:</span>
            </div>

            <List class="permissions group mt-2">
                <ListItem v-for="permission in requestedPermissions" content-classes="opacity-50" class="first:mt-0 last:mb-0 my-4">
                    <template v-slot:icon>
                        <component :is="permission.icon" :size="32" class="w-16 opacity-50" />
                    </template>

                    <span>{{ permission.title }}</span>
                    <span class="text-sm">{{ permission.description }}</span>
                </ListItem>
            </List>
        </div>

        <template v-slot:footer>
            <Button class="w-4/12 mr-2" :gray="true">Deny</Button>
            <Button class="w-8/12 ml-2" :success="true" :loading="allowLoading" @click="allowAccess">Allow</Button>
        </template>
    </Card>
</template>

<style scoped>

</style>
