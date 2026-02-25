<template>
  <div class="cda-admin-card cda-settings-form">
    <h2 class="cda-title">
      {{ t('modal_settings') }}
    </h2>

    <div class="cda-toggle-row">
      <div class="cda-toggle-row-left">
        <span class="cda-label">{{ t('enable_modal') }}</span>
        <span class="cda-toggle-state" :class="form.enable_modal ? 'is-on' : 'is-off'">
          {{ form.enable_modal ? 'Actif' : 'Inactif' }}
        </span>
      </div>
      <ToggleSwitch
        v-model="form.enable_modal"
        :disabled="isFieldDisabled('enable_modal')"
      />
    </div>

    <div class="cda-field-group">
      <label class="cda-label">
        {{ t('redirect_after_login') }}
      </label>
      <input
        v-model="form.redirect_after_login"
        type="text"
        class="cda-input"
        :disabled="isFieldDisabled('redirect_after_login')"
      />
    </div>

    <div class="cda-field-group">
      <label class="cda-label">
        {{ t('redirect_after_logout') }}
      </label>
      <input
        v-model="form.redirect_after_logout"
        type="text"
        class="cda-input"
        :disabled="isFieldDisabled('redirect_after_logout')"
      />
    </div>

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
          :disabled="isFieldDisabled('security_max_attempts')"
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
          :disabled="isFieldDisabled('security_lock_time')"
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
          :disabled="isFieldDisabled('rest_max_requests')"
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
          :disabled="isFieldDisabled('rest_window')"
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
      <ToggleSwitch
        v-model="form.cloudflare_enabled"
        :disabled="isFieldDisabled('cloudflare_enabled')"
      />
    </div>

    <div class="cda-field-group">
      <label class="cda-label">
        {{ t('turnstile_site_key') }}
      </label>
      <input
        v-model="form.turnstile_site_key"
        type="text"
        class="cda-input"
        autocomplete="off"
        spellcheck="false"
        :disabled="isFieldDisabled('turnstile_site_key')"
      />
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
          :disabled="isFieldDisabled('cloudflare_secret')"
        />
        <button
          type="button"
          class="cda-password-toggle"
          :aria-label="showCloudflareSecret ? t('hide_secret') : t('show_secret')"
          :aria-pressed="showCloudflareSecret ? 'true' : 'false'"
          :disabled="isFieldDisabled('cloudflare_secret')"
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

    <FloatingButtonSettings
      :form="form"
      :is-field-disabled="isFieldDisabled"
    />

    <div v-if="multisite.enabled" class="cda-multisite-controls">
      <div class="cda-divider"></div>

      <h3 class="cda-subtitle">
        {{ t('multisite_controls') }}
      </h3>
      <p class="cda-help-text">
        {{ t('multisite_controls_help') }}
      </p>
      <div class="cda-multisite-grid">

      <div
        v-for="item in multisiteFields"
        :key="item.key"
        class="cda-multisite-row"
      >
        <div class="cda-multisite-row-header">
          <span class="cda-label">{{ t(item.labelKey) }}</span>
          <span v-if="isFieldDisabled(item.key)" class="cda-multisite-locked">
            {{ t('multisite_read_only_subsite') }}
          </span>
        </div>

        <div class="cda-multisite-row-options">
          <label class="cda-option-row">
            <input
              :checked="isDefaultEnabled(item.key)"
              type="checkbox"
              :disabled="!canEditNetworkRules()"
              @change="setDefaultEnabled(item.key, $event.target.checked)"
            />
            {{ t('multisite_default_all_sites') }}
          </label>

          <label
            v-if="isDefaultEnabled(item.key)"
            class="cda-option-row"
          >
            <input
              :checked="isLockEnabled(item.key)"
              type="checkbox"
              :disabled="!canEditNetworkRules()"
              @change="setLockEnabled(item.key, $event.target.checked)"
            />
            {{ t('multisite_lock_main_site') }}
          </label>
        </div>
        </div>
      </div>
    </div>



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

const multisiteFields = [
  { key: 'enable_modal', labelKey: 'enable_modal' },
  { key: 'redirect_after_login', labelKey: 'redirect_after_login' },
  { key: 'redirect_after_logout', labelKey: 'redirect_after_logout' },
  { key: 'security_max_attempts', labelKey: 'max_login_attempts' },
  { key: 'security_lock_time', labelKey: 'lock_time_seconds' },
  { key: 'rest_max_requests', labelKey: 'rest_max_requests' },
  { key: 'rest_window', labelKey: 'rest_window_seconds' },
  { key: 'cloudflare_enabled', labelKey: 'enable_cloudflare' },
  { key: 'turnstile_site_key', labelKey: 'turnstile_site_key' },
  { key: 'cloudflare_secret', labelKey: 'cloudflare_secret_key' },
  { key: 'floating_position', labelKey: 'floating_position' },
  { key: 'floating_size_mobile', labelKey: 'floating_size_mobile' },
  { key: 'floating_size_tablet', labelKey: 'floating_size_tablet' },
  { key: 'floating_size_desktop', labelKey: 'floating_size_desktop' },
  { key: 'floating_custom_classes', labelKey: 'floating_custom_css' },
  { key: 'floating_label_login', labelKey: 'floating_label_login' },
  { key: 'floating_label_logout', labelKey: 'floating_label_logout' },
  { key: 'show_label_mobile', labelKey: 'show_label_mobile' },
  { key: 'show_label_tablet', labelKey: 'show_label_tablet' },
  { key: 'show_label_desktop', labelKey: 'show_label_desktop' },
]

const controlKeys = multisiteFields.map((field) => field.key)

function createBooleanMap(initialValue = false) {
  return controlKeys.reduce((acc, key) => {
    acc[key] = initialValue
    return acc
  }, {})
}

function normalizeMultisite(raw) {
  const defaults = createBooleanMap(false)
  const locks = createBooleanMap(false)
  const locked = createBooleanMap(false)

  if (raw?.controls?.defaults) {
    for (const key of controlKeys) {
      defaults[key] = !!raw.controls.defaults[key]
    }
  }

  if (raw?.controls?.locks) {
    for (const key of controlKeys) {
      locks[key] = defaults[key] && !!raw.controls.locks[key]
    }
  }

  if (raw?.locked) {
    for (const key of controlKeys) {
      locked[key] = !!raw.locked[key]
    }
  }

  return {
    enabled: !!raw?.enabled,
    is_master: !!raw?.is_master,
    controls: {
      defaults,
      locks,
    },
    locked,
  }
}

function applyMultisiteState(raw) {
  const normalized = normalizeMultisite(raw)
  multisite.enabled = normalized.enabled
  multisite.is_master = normalized.is_master
  multisite.controls.defaults = normalized.controls.defaults
  multisite.controls.locks = normalized.controls.locks
  multisite.locked = normalized.locked
}

const form = reactive({
  ...window.CorbidevModalAuth.settings,
  turnstile_site_key: window.CorbidevModalAuth.settings?.turnstile_site_key ?? '',
  cloudflare_secret: window.CorbidevModalAuth.settings?.cloudflare_secret ?? '',
})

const multisite = reactive(
  normalizeMultisite(window.CorbidevModalAuth.multisite)
)

const loading = ref(false)
const saved = ref(false)
const error = ref(false)
const showCloudflareSecret = ref(false)

function canEditNetworkRules() {
  return multisite.enabled && multisite.is_master
}

function isFieldDisabled(key) {
  return multisite.enabled && !multisite.is_master && !!multisite.locked?.[key]
}

function isDefaultEnabled(key) {
  return !!multisite.controls.defaults?.[key]
}

function isLockEnabled(key) {
  return !!multisite.controls.locks?.[key]
}

function setDefaultEnabled(key, enabled) {
  multisite.controls.defaults[key] = !!enabled

  if (!enabled) {
    multisite.controls.locks[key] = false
  }
}

function setLockEnabled(key, enabled) {
  multisite.controls.locks[key] = isDefaultEnabled(key) && !!enabled
}

async function save() {
  loading.value = true
  saved.value = false
  error.value = false

  try {
    const payload = { ...form }

    if (multisite.enabled && multisite.is_master) {
      payload._multisite_controls = multisite.controls
    }

    const response = await fetch(
      `${window.CorbidevModalAuth.restUrl}/settings`,
      {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': window.CorbidevModalAuth.nonce,
        },
        body: JSON.stringify(payload),
      }
    )

    if (!response.ok) {
      throw new Error()
    }

    const data = await response.json()
    Object.assign(form, data.settings)

    if (data.multisite) {
      applyMultisiteState(data.multisite)
    }

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
