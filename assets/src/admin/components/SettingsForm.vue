<template>
  <div class="cda-admin-card space-y-6">

    <h2 class="cda-title">
      {{ t('modal_settings') }}
    </h2>

    <!-- Activation Modal -->
    <div class="flex items-center justify-between">
      <span>{{ t('enable_modal') }}</span>
      <ToggleSwitch v-model="form.enable_modal" />
    </div>

    <!-- Redirect Login -->
    <div class="space-y-2">
      <label class="cda-label">
        {{ t('redirect_after_login') }}
      </label>
      <input
        v-model="form.redirect_after_login"
        type="text"
        class="cda-input"
      />
    </div>

    <!-- Redirect Logout -->
    <div class="space-y-2">
      <label class="cda-label">
        {{ t('redirect_after_logout') }}
      </label>
      <input
        v-model="form.redirect_after_logout"
        type="text"
        class="cda-input"
      />
    </div>

    <!-- Sécurité -->
    <div class="cda-divider"></div>

    <h3 class="cda-subtitle">
      {{ t('security') }}
    </h3>

    <div class="grid grid-cols-2 gap-4">

      <div>
        <label class="cda-label">
          {{ t('max_login_attempts') }}
        </label>
        <input
          v-model.number="form.security_max_attempts"
          type="number"
          min="1"
          class="cda-input"
        />
      </div>

      <div>
        <label class="cda-label">
          {{ t('lock_time_seconds') }}
        </label>
        <input
          v-model.number="form.security_lock_time"
          type="number"
          min="60"
          class="cda-input"
        />
      </div>

      <div>
        <label class="cda-label">
          {{ t('rest_max_requests') }}
        </label>
        <input
          v-model.number="form.rest_max_requests"
          type="number"
          min="1"
          class="cda-input"
        />
      </div>

      <div>
        <label class="cda-label">
          {{ t('rest_window_seconds') }}
        </label>
        <input
          v-model.number="form.rest_window"
          type="number"
          min="10"
          class="cda-input"
        />
      </div>

    </div>

    <!-- Save -->
    <div class="flex items-center gap-4">
      <button
        class="cda-button"
        :disabled="loading"
        @click="save"
      >
        {{ loading ? t('saving') : t('save') }}
      </button>

      <span v-if="saved" class="text-green-600 text-sm">
        {{ t('saved') }}
      </span>

      <span v-if="error" class="text-red-600 text-sm">
        {{ t('error') }}
      </span>
    </div>

  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import ToggleSwitch from './ToggleSwitch.vue'
import { useI18n } from '../../composables/useI18n'

const { t } = useI18n()

const form = reactive({ ...window.CorbidevModalAuth.settings })

const loading = ref(false)
const saved = ref(false)
const error = ref(false)

async function save() {
  loading.value = true
  saved.value = false
  error.value = false

  try {
    const response = await fetch(
      `${window.CorbidevModalAuth.restUrl}/settings`,
      {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.CorbidevModalAuth.nonce,
        },
        body: JSON.stringify(form),
      }
    )

    if (!response.ok) {
      throw new Error()
    }

    const data = await response.json()

    Object.assign(form, data.settings)

    saved.value = true
  } catch {
    error.value = true
  } finally {
    loading.value = false

    setTimeout(() => {
      saved.value = false
      error.value = false
    }, 3000)
  }
}
</script>