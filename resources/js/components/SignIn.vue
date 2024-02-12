<script>
import {defineComponent} from 'vue'
import InputField from "../ui/InputField.vue";
import Button from "../ui/Button.vue";
import Card from "../ui/Card.vue";
import GoogleSignIn from "./GoogleSignIn.vue";
import axios from "axios";

export default defineComponent({
    name: "App",
    components: {GoogleSignIn, Button, InputField, Card},
    data() {
        return {
            email: '',
            password: '',
            repeat_password: '',
            loginLoading: false,

            fields: {
                email: {
                    error: false,
                    message: ''
                },
                password: {
                    error: false,
                    message: ''
                }
            }
        }
    },
    methods: {
        logIn() {
            if (this.validate()) {
                const {email, password} = this;
                const params = window.authorize_reuqest;
                this.loginLoading = true;

                axios.post('/api/login', { email, password, params })
                    .then(response => response.data)
                    .then(response => localStorage.setItem('token', response.token.accessToken))
                    .then(() => this.resetErrors())
                    .then(() => this.$emit('signed-in'))
                    .catch(err => this.handleError(err.response.data))
                    .finally(() => this.loginLoading = false)
                ;
            }
        },
        handleError(err) {
            const {errors} = err;
            for (let key in errors) {
                const errorMessage = errors[key].join(' ');
                this.fields[key].error = true;
                this.fields[key].message = errorMessage;
            }
        },
        resetErrors() {
            this.fields.email.error = false;
            this.fields.password.error = false;
        },
        validate() {
            let hasErrors = false;

            if (this.email.length < 5) {
                this.fields.email.error = true;
                this.fields.email.message = 'E-Mail is required and should be longer then 5 symbols';
                hasErrors = true;
            } else {
                this.fields.email.error = false;
                this.fields.email.message = '';
            }

            return !hasErrors;
        }
    }
})
</script>

<template>
    <Card
        title="Sign In"
        description="Using email and password"
        :no-footer="true"
    >
        <form class="form mb-3">
            <InputField label="E-Mail" name="email" type="email" v-model="email" :error="fields.email.error" :error-message="fields.email.message"></InputField>
            <InputField label="Password" name="password" type="password" v-model="password" :error="fields.password.error" :error-message="fields.password.message"></InputField>
            <Button class="mt-6" @click="logIn" :loading="loginLoading">Sign In</Button>
            <GoogleSignIn class="mt-2"></GoogleSignIn>
        </form>
    </Card>
</template>

<style scoped>

</style>
