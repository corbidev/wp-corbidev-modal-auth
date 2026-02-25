<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuth } from '../composables/useAuth'
import { useI18n } from '../composables/useI18n'

const { t } = useI18n()

const {
  isLoggedIn,
  login,
  logout,
  lostPassword,
  loading,
  errorCode,
  checkSession,
} = useAuth()

const settings = window.CorbidevModalAuth?.settings || {}

const isOpen = ref(false)
const mode = ref('login') // login | lost

const form = ref({
  username: '',
  password: '',
  remember: false,
})

const lostEmail = ref('')

/**
 * Computed
 */
const canRemember = computed(() => settings.enable_remember_me)
const canLostPassword = computed(() => settings.enable_lost_password)
const showLoginButton = computed(() => !isLoggedIn.value)
const showLogoutButton = computed(() => isLoggedIn.value)

/**
 * UI Actions
 */
function open() {
  isOpen.value = true
}

function close() {
  isOpen.value = false
}

async function submitLogin() {
  await login({
    username: form.value.username,
    password: form.value.password,
    remember: form.value.remember,
  })
}

async function submitLogout() {
  await logout()
}

async function submitLostPassword() {
  await lostPassword(lostEmail.value)
  mode.value = 'login'
}

onMounted(async () => {
  await checkSession()

  if (settings.auto_open && !isLoggedIn.value) {
    isOpen.value = true
  }
})
</script>

<template>
  <div>
    <!-- Bouton Login -->
    <button
      v-if="showLoginButton"
      @click="open"
      class="cda-trigger cda-trigger--login"
    >
      {{ t('login') }}
    </button>

    <!-- Bouton Logout -->
    <button
      v-if="showLogoutButton"
      @click="submitLogout"
      :disabled="loading"
      class="cda-trigger cda-trigger--logout"
    >
      {{ t('logout') }}
    </button>

    <!-- Modal -->
    <div
      v-if="isOpen"
      class="cda-modal"
    >
      <div class="cda-modal-card">
        <!-- Close -->
        <button
          @click="close"
          class="cda-modal-close"
        >
          ✕
        </button>

        <!-- Title -->
        <h2 class="cda-modal-title">
          {{ mode === 'login' ? t('login') : t('lost_password') }}
        </h2>

        <!-- Error -->
        <div
          v-if="errorCode"
          class="cda-modal-error"
        >
          {{ t(errorCode) }}
        </div>

        <!-- LOGIN FORM -->
        <form
          v-if="mode === 'login'"
          @submit.prevent="submitLogin"
          class="cda-modal-form"
        >
          <input
            v-model="form.username"
            type="text"
            class="cda-modal-input"
            :placeholder="t('username')"
          />

          <input
            v-model="form.password"
            type="password"
            class="cda-modal-input"
            :placeholder="t('password')"
          />

          <div
            v-if="canRemember"
            class="cda-modal-checkline"
          >
            <input type="checkbox" v-model="form.remember" />
            <span>{{ t('remember_me') }}</span>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="cda-modal-submit"
          >
            {{ loading ? t('loading') : t('login') }}
          </button>

          <div
            v-if="canLostPassword"
            class="cda-modal-linkrow"
          >
            <button
              type="button"
              @click="mode = 'lost'"
              class="cda-modal-link"
            >
              {{ t('forgot_password') }}
            </button>
          </div>
        </form>

        <!-- LOST PASSWORD FORM -->
        <form
          v-else
          @submit.prevent="submitLostPassword"
          class="cda-modal-form"
        >
          <input
            v-model="lostEmail"
            type="email"
            class="cda-modal-input"
            :placeholder="t('email')"
          />

          <button
            type="submit"
            :disabled="loading"
            class="cda-modal-submit"
          >
            {{ loading ? t('loading') : t('reset_password') }}
          </button>

          <div class="cda-modal-linkrow">
            <button
              type="button"
              @click="mode = 'login'"
              class="cda-modal-link"
            >
              {{ t('back_to_login') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>