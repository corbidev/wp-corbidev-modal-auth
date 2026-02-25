<template>
  <div class="cda-admin-card cda-settings-form">

    <h2 class="cda-title">
      {{ t('modal_settings') }}
    </h2>

    <!-- Activation Modal -->
    <div class="cda-toggle-row">
      <div class="cda-toggle-row-left">
        <span class="cda-label">{{ t('enable_modal') }}</span>
        <span class="cda-toggle-state" :class="form.enable_modal ? 'is-on' : 'is-off'">
          {{ form.enable_modal ? 'Actif' : 'Inactif' }}
        </span>
      </div>
      <ToggleSwitch v-model="form.enable_modal" />
    </div>

    <!-- Redirect Login -->
    <div class="cda-field-group">
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
    <div class="cda-field-group">
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

    <div class="cda-settings-grid">

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

    <div class="cda-divider"></div>

    <h3 class="cda-subtitle">
      {{ t('cloudflare_settings') }}
    </h3>

    <div class="cda-toggle-row">
      <div class="cda-toggle-row-left">
        <span class="cda-label">{{ t('enable_cloudflare') }}</span>
        <span class="cda-toggle-state" :class="form.cloudflare_enabled ? 'is-on' : 'is-off'">
          {{ form.cloudflare_enabled ? 'Actif' : 'Inactif' }}
        </span>
      </div>
      <ToggleSwitch v-model="form.cloudflare_enabled" />
    </div>

    <div class="cda-field-group">
      <label class="cda-label">
        {{ t('cloudflare_secret_key') }}
      </label>
      <div class="cda-password-field">
        <input
          v-model="form.cloudflare_secret"
          :type="showCloudflareSecret ? 'text' : 'password'"
          class="cda-input cda-input--with-toggle"
          autocomplete="new-password"
          spellcheck="false"
        />
        <button
          type="button"
          class="cda-password-toggle"
          :aria-label="showCloudflareSecret ? t('hide_secret') : t('show_secret')"
          :aria-pressed="showCloudflareSecret ? 'true' : 'false'"
          @click="showCloudflareSecret = !showCloudflareSecret"
        >
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
        </button>
      </div>
      <p class="cda-help-text">
        {{ t('cloudflare_secret_help') }}
      </p>
    </div>

    <FloatingButtonSettings :form="form" />

    <!-- Save -->
    <div class="cda-settings-actions">
      <button
        class="cda-button"
        :disabled="loading"
        @click="save"
      >
        {{ loading ? t('saving') : t('save') }}
      </button>

      <span v-if="saved" class="cda-save-success">
        {{ t('saved') }}
      </span>

      <span v-if="error" class="cda-save-error">
        {{ t('error') }}
      </span>
    </div>

  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import ToggleSwitch from './ToggleSwitch.vue'
import FloatingButtonSettings from './FloatingButtonSettings.vue'
import { useI18n } from '../../composables/useI18n'

const { t } = useI18n()

const form = reactive({
  ...window.CorbidevModalAuth.settings,
  cloudflare_secret: window.CorbidevModalAuth.settings?.cloudflare_secret ?? '',
})

const loading = ref(false)
const saved = ref(false)
const error = ref(false)
const showCloudflareSecret = ref(false)

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
