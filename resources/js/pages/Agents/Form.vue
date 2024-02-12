<script setup lang="ts">

import InputField from "@/ui/InputField.vue";
import Button from "@/ui/Button.vue";
import {ref, watch} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";

const router = useRouter()
const props = defineProps({
  data: {
    type: Object,
    default: null
  },
  isEdit: {
    type: Boolean,
    default: false
  },
  id: {
    default: null
  }
})

const agent = ref(props.data);
const fields = ref({
  name: {
    error: false,
    message: ''
  },
  email: {
    error: false,
    message: '',
  },
  password: {
    error: false,
    message: ''
  },
  re_password: {
    error: false,
    message: ''
  }
});
const isLoading = ref(false);

watch(() => props.data, (first, second) => {
  agent.value = first;
})

const createAgent = () => {
  if (!props.isEdit && (agent.value.password !== agent.value.re_password)) {
    return ;
  }

  isLoading.value = true;

  const promise = props.isEdit
    ? axios.put(`/api/agents/${props.id}`, agent.value)
    : axios.post('/api/agents', agent.value);

  promise
    .then(response => response.data)
    .then(response => router.push('/agents'))
    .catch(err => handleError(err.response.data))
    .finally(() => isLoading.value = false);
}

const handleError = (err) => {
  const {errors} = err;
  for (let key in errors) {
    const errorMessage = errors[key].join(' ');
    fields.value[key].error = true;
    fields.value[key].message = errorMessage;
  }
}
</script>

<template>
<div v-if="props.data !== null">
  <InputField
    name="name"
    label="ФІО Агента"
    :error="fields.name.error"
    :error-message="fields.name.message"
    v-model="agent.name"
  ></InputField>

  <InputField
    name="name"
    label="Імейл"
    type="email"
    :error="fields.email.error"
    :error-message="fields.email.message"
    v-model="agent.email"
  ></InputField>

  <InputField
    name="password"
    label="Пароль"
    type="password"
    :error="fields.password.error"
    :error-message="fields.password.message"
    v-model="agent.password"
  ></InputField>

  <InputField
    name="password"
    label="Повторіть пароль"
    type="password"
    :error="fields.re_password.error"
    :error-message="fields.re_password.message"
    v-model="agent.re_password"
  ></InputField>

  <Button :loading="isLoading" @click="createAgent">{{ props.isEdit ? 'Оновити' : 'Створити' }}</Button>
</div>
</template>

<style scoped>

</style>
