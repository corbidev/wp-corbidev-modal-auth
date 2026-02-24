<template>
  <button
    type="button"
    class="cda-toggle"
    :class="{ 'cda-toggle--active': modelValue }"
    @click="toggle"
  >
    <span class="cda-toggle-thumb"></span>
  </button>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['update:modelValue'])

function toggle() {
  emit('update:modelValue', !props.modelValue)
}
</script>