<script setup lang="ts">
import Card from "@/ui/Card.vue";
import {computed, ref} from "vue";
import { state, user } from "@/global-state";
import Button from "@/ui/Button.vue";

const saving = ref(false);

const appId = computed(() => {
  if (user.value === null) {
    return ;
  }

  return user.value.organizations[0].app_id;
})

const widgetPath = computed(() => {
  return window.widget_path;
})
</script>

<template>
<div class="p-8">
  <Card title="Інструкція" description="З підключення сайту до сервісу підтримки" :no-footer="true">
    Для підключення сайту необхідно в кінці сторінки, якомога ближче до &lt;/body&gt;, додати наступний код

    <div class="mt-4 mb-4 rounded rounded-lg shadow shadow-md overflow-hidden">
      <div class="p-4 text-sm bg-gray-700 text-white">Скрипт підключення</div>
      <pre class="p-4 text-sm">
&lt;script src="{{ widgetPath }}"&gt;&lt;/script&gt;
&lt;script"&gt;
  const params = {
    identifier: '8725619FB651B1DCE905B26C9B915B86132B5EB3', // Any unique identifier'
    name: 'Customer Name'
  };
  initHelpster('{{ appId }}');
&lt;/script&gt;</pre>
    </div>
  </Card>
  <Card v-if="false" class="mt-4" title="Налаштування віджету" description="Налаштуйте як буде виглядати віджет на Вашом сайті" :no-footer="true">
    <div class="w-96">
      <Button :loading="saving">Зберігти</Button>
    </div>
  </Card>
</div>
</template>

<style scoped>

</style>
