import { a as openBlock, b as createElementBlock, f as createBaseVNode, n as normalizeClass, u as useI18n, k as reactive, t as toDisplayString, d as unref, l as createVNode, g as withDirectives, v as vModelText, e as createCommentVNode, r as ref, j as createApp } from "./tailwind-BJYhuhMy.js";
const _sfc_main$2 = {
  __name: "ToggleSwitch",
  props: {
    modelValue: {
      type: Boolean,
      required: true
    }
  },
  emits: ["update:modelValue"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    function toggle() {
      emit("update:modelValue", !props.modelValue);
    }
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("button", {
        type: "button",
        class: normalizeClass(["cda-toggle", { "cda-toggle--active": __props.modelValue }]),
        onClick: toggle
      }, [..._cache[0] || (_cache[0] = [
        createBaseVNode("span", { class: "cda-toggle-thumb" }, null, -1)
      ])], 2);
    };
  }
};
const _hoisted_1$1 = { class: "cda-admin-card space-y-6" };
const _hoisted_2 = { class: "cda-title" };
const _hoisted_3 = { class: "flex items-center justify-between" };
const _hoisted_4 = { class: "space-y-2" };
const _hoisted_5 = { class: "cda-label" };
const _hoisted_6 = { class: "space-y-2" };
const _hoisted_7 = { class: "cda-label" };
const _hoisted_8 = { class: "cda-subtitle" };
const _hoisted_9 = { class: "grid grid-cols-2 gap-4" };
const _hoisted_10 = { class: "cda-label" };
const _hoisted_11 = { class: "cda-label" };
const _hoisted_12 = { class: "cda-label" };
const _hoisted_13 = { class: "cda-label" };
const _hoisted_14 = { class: "flex items-center gap-4" };
const _hoisted_15 = ["disabled"];
const _hoisted_16 = {
  key: 0,
  class: "text-green-600 text-sm"
};
const _hoisted_17 = {
  key: 1,
  class: "text-red-600 text-sm"
};
const _sfc_main$1 = {
  __name: "SettingsForm",
  setup(__props) {
    const { t } = useI18n();
    const form = reactive({ ...window.CorbidevModalAuth.settings });
    const loading = ref(false);
    const saved = ref(false);
    const error = ref(false);
    async function save() {
      loading.value = true;
      saved.value = false;
      error.value = false;
      try {
        const response = await fetch(
          `${window.CorbidevModalAuth.restUrl}/settings`,
          {
            method: "POST",
            credentials: "include",
            headers: {
              "Content-Type": "application/json",
              "X-WP-Nonce": window.CorbidevModalAuth.nonce
            },
            body: JSON.stringify(form)
          }
        );
        if (!response.ok) {
          throw new Error();
        }
        const data = await response.json();
        Object.assign(form, data.settings);
        saved.value = true;
      } catch {
        error.value = true;
      } finally {
        loading.value = false;
        setTimeout(() => {
          saved.value = false;
          error.value = false;
        }, 3e3);
      }
    }
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1$1, [
        createBaseVNode("h2", _hoisted_2, toDisplayString(unref(t)("modal_settings")), 1),
        createBaseVNode("div", _hoisted_3, [
          createBaseVNode("span", null, toDisplayString(unref(t)("enable_modal")), 1),
          createVNode(_sfc_main$2, {
            modelValue: form.enable_modal,
            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => form.enable_modal = $event)
          }, null, 8, ["modelValue"])
        ]),
        createBaseVNode("div", _hoisted_4, [
          createBaseVNode("label", _hoisted_5, toDisplayString(unref(t)("redirect_after_login")), 1),
          withDirectives(createBaseVNode("input", {
            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => form.redirect_after_login = $event),
            type: "text",
            class: "cda-input"
          }, null, 512), [
            [vModelText, form.redirect_after_login]
          ])
        ]),
        createBaseVNode("div", _hoisted_6, [
          createBaseVNode("label", _hoisted_7, toDisplayString(unref(t)("redirect_after_logout")), 1),
          withDirectives(createBaseVNode("input", {
            "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => form.redirect_after_logout = $event),
            type: "text",
            class: "cda-input"
          }, null, 512), [
            [vModelText, form.redirect_after_logout]
          ])
        ]),
        _cache[7] || (_cache[7] = createBaseVNode("div", { class: "cda-divider" }, null, -1)),
        createBaseVNode("h3", _hoisted_8, toDisplayString(unref(t)("security")), 1),
        createBaseVNode("div", _hoisted_9, [
          createBaseVNode("div", null, [
            createBaseVNode("label", _hoisted_10, toDisplayString(unref(t)("max_login_attempts")), 1),
            withDirectives(createBaseVNode("input", {
              "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => form.security_max_attempts = $event),
              type: "number",
              min: "1",
              class: "cda-input"
            }, null, 512), [
              [
                vModelText,
                form.security_max_attempts,
                void 0,
                { number: true }
              ]
            ])
          ]),
          createBaseVNode("div", null, [
            createBaseVNode("label", _hoisted_11, toDisplayString(unref(t)("lock_time_seconds")), 1),
            withDirectives(createBaseVNode("input", {
              "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => form.security_lock_time = $event),
              type: "number",
              min: "60",
              class: "cda-input"
            }, null, 512), [
              [
                vModelText,
                form.security_lock_time,
                void 0,
                { number: true }
              ]
            ])
          ]),
          createBaseVNode("div", null, [
            createBaseVNode("label", _hoisted_12, toDisplayString(unref(t)("rest_max_requests")), 1),
            withDirectives(createBaseVNode("input", {
              "onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => form.rest_max_requests = $event),
              type: "number",
              min: "1",
              class: "cda-input"
            }, null, 512), [
              [
                vModelText,
                form.rest_max_requests,
                void 0,
                { number: true }
              ]
            ])
          ]),
          createBaseVNode("div", null, [
            createBaseVNode("label", _hoisted_13, toDisplayString(unref(t)("rest_window_seconds")), 1),
            withDirectives(createBaseVNode("input", {
              "onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => form.rest_window = $event),
              type: "number",
              min: "10",
              class: "cda-input"
            }, null, 512), [
              [
                vModelText,
                form.rest_window,
                void 0,
                { number: true }
              ]
            ])
          ])
        ]),
        createBaseVNode("div", _hoisted_14, [
          createBaseVNode("button", {
            class: "cda-button",
            disabled: loading.value,
            onClick: save
          }, toDisplayString(loading.value ? unref(t)("saving") : unref(t)("save")), 9, _hoisted_15),
          saved.value ? (openBlock(), createElementBlock("span", _hoisted_16, toDisplayString(unref(t)("saved")), 1)) : createCommentVNode("", true),
          error.value ? (openBlock(), createElementBlock("span", _hoisted_17, toDisplayString(unref(t)("error")), 1)) : createCommentVNode("", true)
        ])
      ]);
    };
  }
};
const _hoisted_1 = { class: "p-8" };
const _sfc_main = {
  __name: "App",
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        createVNode(_sfc_main$1)
      ]);
    };
  }
};
createApp(_sfc_main).mount("#corbidev-modal-auth-admin-app");
//# sourceMappingURL=admin-1BNqO9PH.js.map
