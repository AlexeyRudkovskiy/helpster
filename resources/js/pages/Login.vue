<script>
import {defineComponent} from 'vue'
import InputField from "@/ui/InputField.vue";
import Button from "@/ui/Button.vue";
import Card from "@/ui/Card.vue";
import axios from "axios";
import EmptyLayout from "@/layouts/EmptyLayout.vue";

export default defineComponent({
  name: "Login",
  components: {EmptyLayout, Button, InputField, Card},
  data() {
    return {
      email: '',
      password: '',
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
    createAccount() {
      this.$router.push('/register')
    },
    logIn() {
      if (this.validate()) {
        const {email, password} = this;
        this.loginLoading = true;

        axios
          .get('/sanctum/csrf-cookie')
          .then(() => axios.post('/api/login', { email, password }))
          .then(response => response.data)
          .then(() => this.resetErrors())
          .then(() => this.$router.push('/home'))
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
  <EmptyLayout>
    <Card
      title="Sign In"
      description="Using email and password"
      :no-footer="true"
    >
      <form class="form mb-3">
        <InputField label="E-Mail" name="email" type="email" v-model="email" :error="fields.email.error" :error-message="fields.email.message"></InputField>
        <InputField label="Password" name="password" type="password" v-model="password" :error="fields.password.error" :error-message="fields.password.message"></InputField>
        <Button class="mt-6" @click="logIn" :loading="loginLoading">Sign In</Button>
        <Button class="mt-2" @click="createAccount" :gray="true">Create account</Button>
      </form>
    </Card>
  </EmptyLayout>
</template>

<style scoped>

</style>
