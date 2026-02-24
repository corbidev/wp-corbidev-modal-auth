<template>
  <div class="cda-admin-card">
    <h2 class="cda-title">Modal Settings</h2>

    <ToggleSwitch v-model="form.enabled" />

    <button class="cda-button" @click="save">
      Save
    </button>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import ToggleSwitch from './ToggleSwitch.vue'

const form = reactive(window.CDA_ADMIN.settings)

function save() {
  fetch('/wp-json/corbidev/v1/settings', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': window.CDA_ADMIN.nonce
    },
    body: JSON.stringify(form)
  })
}
</script>