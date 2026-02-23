<template>
  <div class="cda-modal">
    <div class="cda-card">
      <h2 class="cda-title">{{ t('login') }}</h2>

      <input v-model="username" class="cda-input" :placeholder="t('username')" />
      <input v-model="password" type="password" class="cda-input" :placeholder="t('password')" />

      <button class="cda-button" @click="submit">
        {{ t('login') }}
      </button>

      <p v-if="error" class="cda-error">
        {{ error }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from '@app/composables/useI18n'
import { login } from '@core/api'

const { t } = useI18n()

const username = ref('')
const password = ref('')
const error = ref(null)

async function submit() {
  const res = await login(username.value, password.value)

  if (!res.success) {
    error.value = res.message
  } else {
    window.location.reload()
  }
}
</script>