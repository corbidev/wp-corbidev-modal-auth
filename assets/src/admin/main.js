import { createApp, nextTick } from 'vue'
import AdminApp from './App.vue'
import '@styles/tailwind.css'

const shellEl = document.getElementById('corbidev-modal-auth-admin-shell')
const appEl = document.getElementById('corbidev-modal-auth-admin-app')
const loaderEl = document.getElementById('corbidev-modal-auth-admin-loading')

createApp(AdminApp).mount('#corbidev-modal-auth-admin-app')

nextTick(() => {
  requestAnimationFrame(() => {
    shellEl?.classList.remove('is-loading')
    shellEl?.classList.add('is-ready')
    appEl?.setAttribute('aria-busy', 'false')
    loaderEl?.remove()
  })
})
