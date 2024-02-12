<script>
import {defineComponent} from 'vue'
import SignIn from "./SignIn.vue";
import AskAccess from "./AskAccess.vue";
import Card from "../ui/Card.vue";
import InputField from "../ui/InputField.vue";
import Button from "../ui/Button.vue";
import axios from "axios";

export default defineComponent({
    name: "App",
    components: {InputField, Card, SignIn, AskAccess, Button},
    data() {
        return {
            step: 'sign-in',
            text: '',
            state: false,
            user: null
        }
    },
    methods: {
        askAccess() {
            this.step = 'ask-access';
        },
        toggle() {
            this.state = !this.state;
        }
    },
    computed: {
        authenticated() {
            return window.authorize_reuqest.authenticated;
        }
    },
    mounted() {
        if (this.authenticated) {
            this.step = 'ask-access';
            return ;
        }

        if (typeof localStorage.token === "undefined") {
            return ;
        }

        axios.get('/api/user')
            .then(response => response.data)
            .then(user => this.user = user)
            .then(() => this.step = 'ask-access');
    }
})
</script>

<template>
    <div class="mx-auto my-32 w-96">
        <Card v-if="step === 'test'"
              title="Sign In"
              description="Using your password or Google Account"
        >
            Hello world!

            <template v-slot:footer>
                <Button :gray="true">Read more...</Button>
            </template>
        </Card>
        <SignIn @signed-in="askAccess" v-if="step === 'sign-in'" />
        <AskAccess v-if="step==='ask-access'" />
    </div>
</template>

<style scoped>

</style>
