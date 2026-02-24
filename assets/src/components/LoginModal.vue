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
      class="px-4 py-2 rounded-xl bg-primary text-white hover:opacity-90 transition"
    >
      {{ t('login') }}
    </button>

    <!-- Bouton Logout -->
    <button
      v-if="showLogoutButton"
      @click="submitLogout"
      :disabled="loading"
      class="px-4 py-2 rounded-xl bg-gray-800 text-white hover:opacity-90 transition"
    >
      {{ t('logout') }}
    </button>

    <!-- Modal -->
    <div
      v-if="isOpen"
      class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
    >
      <div
        class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8"
      >
        <!-- Close -->
        <button
          @click="close"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition"
        >
          ✕
        </button>

        <!-- Title -->
        <h2 class="text-2xl font-semibold text-center mb-6">
          {{ mode === 'login' ? t('login') : t('lost_password') }}
        </h2>

        <!-- Error -->
        <div
          v-if="errorCode"
          class="mb-4 text-sm text-center text-red-600"
        >
          {{ t(errorCode) }}
        </div>

        <!-- LOGIN FORM -->
        <form
          v-if="mode === 'login'"
          @submit.prevent="submitLogin"
          class="space-y-4"
        >
          <input
            v-model="form.username"
            type="text"
            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
            :placeholder="t('username')"
          />

          <input
            v-model="form.password"
            type="password"
            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
            :placeholder="t('password')"
          />

          <div
            v-if="canRemember"
            class="flex items-center gap-2 text-sm"
          >
            <input type="checkbox" v-model="form.remember" />
            <span>{{ t('remember_me') }}</span>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2 rounded-xl bg-primary text-white hover:opacity-90 transition"
          >
            {{ loading ? t('loading') : t('login') }}
          </button>

          <div
            v-if="canLostPassword"
            class="text-center text-sm mt-2"
          >
            <button
              type="button"
              @click="mode = 'lost'"
              class="text-primary hover:underline"
            >
              {{ t('forgot_password') }}
            </button>
          </div>
        </form>

        <!-- LOST PASSWORD FORM -->
        <form
          v-else
          @submit.prevent="submitLostPassword"
          class="space-y-4"
        >
          <input
            v-model="lostEmail"
            type="email"
            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
            :placeholder="t('email')"
          />

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-2 rounded-xl bg-primary text-white hover:opacity-90 transition"
          >
            {{ loading ? t('loading') : t('reset_password') }}
          </button>

          <div class="text-center text-sm mt-2">
            <button
              type="button"
              @click="mode = 'login'"
              class="text-primary hover:underline"
            >
              {{ t('back_to_login') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>