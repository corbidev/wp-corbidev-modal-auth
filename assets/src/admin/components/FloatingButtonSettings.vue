<template>
  <div class="space-y-4">
    <div class="cda-divider"></div>

    <h3 class="cda-subtitle">{{ t("floating_button_settings") }}</h3>

    <div class="space-y-2">
      <label class="cda-label">{{ t("floating_position") }}</label>
      <div class="grid grid-cols-2 gap-2 text-sm w-xs">
        <label class="flex items-center gap-2">
          <input
            v-model="form.floating_position"
            type="radio"
            value="bottom-right"
          />
          {{ t("bottom_right") }}
        </label>
        <label class="flex items-center gap-2">
          <input
            v-model="form.floating_position"
            type="radio"
            value="bottom-left"
          />
          {{ t("bottom_left") }}
        </label>
        <label class="flex items-center gap-2">
          <input
            v-model="form.floating_position"
            type="radio"
            value="top-right"
          />
          {{ t("top_right") }}
        </label>
        <label class="flex items-center gap-2">
          <input
            v-model="form.floating_position"
            type="radio"
            value="top-left"
          />
          {{ t("top_left") }}
        </label>
      </div>
    </div>

    <div class="space-y-2">
      <label class="cda-label">{{ t("floating_size") }}</label>

      <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
        <div>
          <p class="font-medium mb-2">{{ t("mobile") }}</p>
          <div class="space-y-1 text-sm">
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_mobile"
                type="radio"
                value="sm"
              />
              {{ t("small") }}
            </label>
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_mobile"
                type="radio"
                value="md"
              />
              {{ t("medium") }}
            </label>
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_mobile"
                type="radio"
                value="lg"
              />
              {{ t("large") }}
            </label>
          </div>
        </div>

        <div>
          <p class="font-medium mb-2">{{ t("tablet") }}</p>
          <div class="space-y-1 text-sm">
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_tablet"
                type="radio"
                value="sm"
              />
              {{ t("small") }}
            </label>
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_tablet"
                type="radio"
                value="md"
              />
              {{ t("medium") }}
            </label>
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_tablet"
                type="radio"
                value="lg"
              />
              {{ t("large") }}
            </label>
          </div>
        </div>

        <div>
          <p class="font-medium mb-2">{{ t("desktop") }}</p>
          <div class="space-y-1 text-sm">
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_desktop"
                type="radio"
                value="sm"
              />
              {{ t("small") }}
            </label>
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_desktop"
                type="radio"
                value="md"
              />
              {{ t("medium") }}
            </label>
            <label class="flex items-center gap-2">
              <input
                v-model="form.floating_size_desktop"
                type="radio"
                value="lg"
              />
              {{ t("large") }}
            </label>
          </div>
        </div>
      </div>
    </div>

    <div class="space-y-2">
      <label class="cda-label">{{ t("floating_custom_css") }}</label>
      <textarea
        v-model="form.floating_custom_classes"
        rows="4"
        class="cda-input"
      ></textarea>
      <p class="text-sm text-gray-500">
        {{ t("floating_custom_css_help") }}
      </p>
    </div>

    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div class="space-y-2">
        <label class="cda-label">{{ t("floating_label_login") }}</label>
        <input
          v-model="form.floating_label_login"
          type="text"
          class="cda-input"
        />
      </div>

      <div class="space-y-2">
        <label class="cda-label">{{ t("floating_label_logout") }}</label>
        <input
          v-model="form.floating_label_logout"
          type="text"
          class="cda-input"
        />
      </div>
    </div>

    <div class="space-y-2">
      <label class="cda-label">{{ t("floating_label_visibility") }}</label>
      <div class="space-y-1 text-sm">
        <label class="flex items-center gap-2">
          <input v-model="form.show_label_mobile" type="checkbox" />
          {{ t("show_label_mobile") }}
        </label>
        <label class="flex items-center gap-2">
          <input v-model="form.show_label_tablet" type="checkbox" />
          {{ t("show_label_tablet") }}
        </label>
        <label class="flex items-center gap-2">
          <input v-model="form.show_label_desktop" type="checkbox" />
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
});

const { t } = useI18n();
</script>
