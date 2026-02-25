<template>
  <button
    type="button"
    class="cda-toggle"
    :class="{
      'cda-toggle--active': modelValue,
      'cda-toggle--disabled': disabled,
    }"
    :disabled="disabled"
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
  },
  disabled: {
    type: Boolean,
    default: false,
  }
})

const emit = defineEmits(['update:modelValue'])

function toggle() {
  if (props.disabled) {
    return
  }

  emit('update:modelValue', !props.modelValue)
}
</script>
