import { ref, computed } from 'vue'
import { login, logout, lostPassword, getCurrentUser } from '../core/api'

const user = ref(null)
const loading = ref(false)
const errorCode = ref(null)

const isLoggedIn = computed(() => !!user.value)

async function checkSession() {
    try {
        const response = await getCurrentUser()
        user.value = response.logged_in ? response : null
    } catch {
        user.value = null
    }
}

async function doLogin(payload) {
    loading.value = true
    errorCode.value = null

    try {
        const response = await login(payload)
        await checkSession()

        if (response.redirect) {
            window.location.href = response.redirect
        }

        return response
    } catch (error) {
        errorCode.value = error.code || 'unknown_error'
        throw error
    } finally {
        loading.value = false
    }
}

async function doLogout() {
    loading.value = true
    errorCode.value = null

    try {
        const response = await logout()
        user.value = null

        if (response.redirect) {
            window.location.href = response.redirect
        }

        return response
    } catch (error) {
        errorCode.value = error.code || 'unknown_error'
        throw error
    } finally {
        loading.value = false
    }
}

async function doLostPassword(user_login) {
    loading.value = true
    errorCode.value = null

    try {
        const response = await lostPassword(user_login)
        return response
    } catch (error) {
        errorCode.value = error.code || 'unknown_error'
        throw error
    } finally {
        loading.value = false
    }
}

export function useAuth() {
    return {
        user,
        loading,
        errorCode,
        isLoggedIn,
        checkSession,
        login: doLogin,
        logout: doLogout,
        lostPassword: doLostPassword,
    }
}