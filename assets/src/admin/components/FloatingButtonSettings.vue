<template>
  <div class="cda-floating-settings">
    <div class="cda-divider"></div>

    <h3 class="cda-subtitle">{{ t("floating_button_settings") }}</h3>

    <div class="cda-field-group">
      <label class="cda-label">{{ t("floating_position") }}</label>
      <div class="cda-floating-position-grid">
        <label class="cda-option-row">
          <input
            v-model="form.floating_position"
            type="radio"
            value="bottom-right"
            :disabled="isFieldDisabled('floating_position')"
          />
          {{ t("bottom_right") }}
        </label>
        <label class="cda-option-row">
          <input
            v-model="form.floating_position"
            type="radio"
            value="bottom-left"
            :disabled="isFieldDisabled('floating_position')"
          />
          {{ t("bottom_left") }}
        </label>
        <label class="cda-option-row">
          <input
            v-model="form.floating_position"
            type="radio"
            value="top-right"
            :disabled="isFieldDisabled('floating_position')"
          />
          {{ t("top_right") }}
        </label>
        <label class="cda-option-row">
          <input
            v-model="form.floating_position"
            type="radio"
            value="top-left"
            :disabled="isFieldDisabled('floating_position')"
          />
          {{ t("top_left") }}
        </label>
      </div>
    </div>

    <div class="cda-field-group">
      <label class="cda-label">{{ t("floating_size") }}</label>

      <div class="cda-floating-size-grid">
        <div class="cda-size-column">
          <p class="cda-size-title">{{ t("mobile") }}</p>
          <div class="cda-size-options">
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_mobile"
                type="radio"
                value="sm"
                :disabled="isFieldDisabled('floating_size_mobile')"
              />
              {{ t("small") }}
            </label>
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_mobile"
                type="radio"
                value="md"
                :disabled="isFieldDisabled('floating_size_mobile')"
              />
              {{ t("medium") }}
            </label>
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_mobile"
                type="radio"
                value="lg"
                :disabled="isFieldDisabled('floating_size_mobile')"
              />
              {{ t("large") }}
            </label>
          </div>
        </div>

        <div class="cda-size-column">
          <p class="cda-size-title">{{ t("tablet") }}</p>
          <div class="cda-size-options">
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_tablet"
                type="radio"
                value="sm"
                :disabled="isFieldDisabled('floating_size_tablet')"
              />
              {{ t("small") }}
            </label>
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_tablet"
                type="radio"
                value="md"
                :disabled="isFieldDisabled('floating_size_tablet')"
              />
              {{ t("medium") }}
            </label>
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_tablet"
                type="radio"
                value="lg"
                :disabled="isFieldDisabled('floating_size_tablet')"
              />
              {{ t("large") }}
            </label>
          </div>
        </div>

        <div class="cda-size-column">
          <p class="cda-size-title">{{ t("desktop") }}</p>
          <div class="cda-size-options">
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_desktop"
                type="radio"
                value="sm"
                :disabled="isFieldDisabled('floating_size_desktop')"
              />
              {{ t("small") }}
            </label>
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_desktop"
                type="radio"
                value="md"
                :disabled="isFieldDisabled('floating_size_desktop')"
              />
              {{ t("medium") }}
            </label>
            <label class="cda-option-row">
              <input
                v-model="form.floating_size_desktop"
                type="radio"
                value="lg"
                :disabled="isFieldDisabled('floating_size_desktop')"
              />
              {{ t("large") }}
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="cda-field-group">
      <label class="cda-label">{{ t("floating_custom_css") }}</label>
      <textarea
        v-model="form.floating_custom_classes"
        rows="4"
        class="cda-input"
        :disabled="isFieldDisabled('floating_custom_classes')"
      ></textarea>
      <p class="cda-help-text">
        {{ t("floating_custom_css_help") }}
      </p>
    </div>

    <div class="cda-floating-labels-grid">
      <div class="cda-field-group">
        <label class="cda-label">{{ t("floating_label_login") }}</label>
        <input
          v-model="form.floating_label_login"
          type="text"
          class="cda-input"
          :disabled="isFieldDisabled('floating_label_login')"
        />
      </div>

      <div class="cda-field-group">
        <label class="cda-label">{{ t("floating_label_logout") }}</label>
        <input
          v-model="form.floating_label_logout"
          type="text"
          class="cda-input"
          :disabled="isFieldDisabled('floating_label_logout')"
        />
      </div>
    </div>

    <div class="cda-field-group">
      <label class="cda-label">{{ t("floating_label_visibility") }}</label>
      <div class="cda-visibility-options">
        <label class="cda-option-row">
          <input
            v-model="form.show_label_mobile"
            type="checkbox"
            :disabled="isFieldDisabled('show_label_mobile')"
          />
          {{ t("show_label_mobile") }}
        </label>
        <label class="cda-option-row">
          <input
            v-model="form.show_label_tablet"
            type="checkbox"
            :disabled="isFieldDisabled('show_label_tablet')"
          />
          {{ t("show_label_tablet") }}
        </label>
        <label class="cda-option-row">
          <input
            v-model="form.show_label_desktop"
            type="checkbox"
            :disabled="isFieldDisabled('show_label_desktop')"
          />
          {{ t("show_label_desktop") }}
        </label>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from "../../composables/useI18n";

defineProps({
  form: {
    type: Object,
    required: true,
  },
  isFieldDisabled: {
    type: Function,
    default: () => false,
  },
});

const { t } = useI18n();
</script>
