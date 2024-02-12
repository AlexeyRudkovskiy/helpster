<script>
import {defineComponent} from 'vue'
import InputField from "@/ui/InputField.vue";
import Button from "@/ui/Button.vue";
import Card from "@/ui/Card.vue";
import axios from "axios";
import EmptyLayout from "@/layouts/EmptyLayout.vue";

export default defineComponent({
  name: "Register",
  components: {EmptyLayout, Button, InputField, Card},
  data() {
    return {
      email: '',
      name: '',
      password: '',
      organization_name: '',
      registerLoading: false,

      fields: {
        email: {
          error: false,
          message: ''
        },
        name: {
          error: false,
          message: ''
        },
        password: {
          error: false,
          message: ''
        },
        organization_name: {
          error: false,
          message: ''
        }
      }
    }
  },
  methods: {
    login() {
      this.$router.push('/login')
    },
    register() {
      if (this.validate()) {
        const {email, name, password, organization_name} = this;
        this.registerLoading = true;

        axios
          .get('/sanctum/csrf-cookie')
          .then(() => axios.post('/api/register', { email, name, password, organization_name }))
          .then(response => response.data)
          .then(() => this.resetErrors())
          .then(() => this.$router.push('/home'))
          .catch(err => this.handleError(err.response.data))
          .finally(() => this.registerLoading = false)
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
      this.fields.name.error = false;
      this.fields.password.error = false;
      this.fields.organization_name.error = false;
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
      title="Sign Up"
      description="Create new account"
      :no-footer="true"
    >
      <form class="form mb-3">
        <InputField label="E-Mail" name="email" type="email" v-model="email" :error="fields.email.error" :error-message="fields.email.message"></InputField>
        <InputField label="Імʼя" name="name" type="text" v-model="name" :error="fields.name.error" :error-message="fields.name.message"></InputField>
        <InputField label="Password" name="password" type="password" v-model="password" :error="fields.password.error" :error-message="fields.password.message"></InputField>
        <InputField label="Organization Name" name="organization_name" type="text" v-model="organization_name" :error="fields.organization_name.error" :error-message="fields.organization_name.message"></InputField>
        <Button class="mt-6" @click="register" :loading="registerLoading">Create Account</Button>
        <Button class="mt-2" @click="login" :gray="true">Sign In</Button>
      </form>
    </Card>
  </EmptyLayout>
</template>

<style scoped>

</style>
