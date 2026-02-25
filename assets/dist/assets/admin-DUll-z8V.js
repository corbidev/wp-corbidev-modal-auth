import { a as openBlock, b as createElementBlock, f as createBaseVNode, n as normalizeClass, u as useI18n, t as toDisplayString, d as unref, g as withDirectives, k as vModelRadio, l as createTextVNode, v as vModelText, h as vModelCheckbox, m as reactive, p as createVNode, e as createCommentVNode, r as ref, j as createApp } from "./tailwind-DqStLozk.js";
const _sfc_main$3 = {
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
const _hoisted_1$2 = { class: "cda-floating-settings" };
const _hoisted_2$1 = { class: "cda-subtitle" };
const _hoisted_3$1 = { class: "cda-field-group" };
const _hoisted_4$1 = { class: "cda-label" };
const _hoisted_5$1 = { class: "cda-floating-position-grid" };
const _hoisted_6$1 = { class: "cda-option-row" };
const _hoisted_7$1 = { class: "cda-option-row" };
const _hoisted_8$1 = { class: "cda-option-row" };
const _hoisted_9$1 = { class: "cda-option-row" };
const _hoisted_10$1 = { class: "cda-field-group" };
const _hoisted_11$1 = { class: "cda-label" };
const _hoisted_12$1 = { class: "cda-floating-size-grid" };
const _hoisted_13$1 = { class: "cda-size-column" };
const _hoisted_14$1 = { class: "cda-size-title" };
const _hoisted_15$1 = { class: "cda-size-options" };
const _hoisted_16$1 = { class: "cda-option-row" };
const _hoisted_17$1 = { class: "cda-option-row" };
const _hoisted_18$1 = { class: "cda-option-row" };
const _hoisted_19$1 = { class: "cda-size-column" };
const _hoisted_20 = { class: "cda-size-title" };
const _hoisted_21 = { class: "cda-size-options" };
const _hoisted_22 = { class: "cda-option-row" };
const _hoisted_23 = { class: "cda-option-row" };
const _hoisted_24 = { class: "cda-option-row" };
const _hoisted_25 = { class: "cda-size-column" };
const _hoisted_26 = { class: "cda-size-title" };
const _hoisted_27 = { class: "cda-size-options" };
const _hoisted_28 = { class: "cda-option-row" };
const _hoisted_29 = { class: "cda-option-row" };
const _hoisted_30 = { class: "cda-option-row" };
const _hoisted_31 = { class: "cda-field-group" };
const _hoisted_32 = { class: "cda-label" };
const _hoisted_33 = { class: "cda-help-text" };
const _hoisted_34 = { class: "cda-floating-labels-grid" };
const _hoisted_35 = { class: "cda-field-group" };
const _hoisted_36 = { class: "cda-label" };
const _hoisted_37 = { class: "cda-field-group" };
const _hoisted_38 = { class: "cda-label" };
const _hoisted_39 = { class: "cda-field-group" };
const _hoisted_40 = { class: "cda-label" };
const _hoisted_41 = { class: "cda-visibility-options" };
const _hoisted_42 = { class: "cda-option-row" };
const _hoisted_43 = { class: "cda-option-row" };
const _hoisted_44 = { class: "cda-option-row" };
const _sfc_main$2 = {
  __name: "FloatingButtonSettings",
  props: {
    form: {
      type: Object,
      required: true
    }
  },
  setup(__props) {
    const { t } = useI18n();
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1$2, [
        _cache[19] || (_cache[19] = createBaseVNode("div", { class: "cda-divider" }, null, -1)),
        createBaseVNode("h3", _hoisted_2$1, toDisplayString(unref(t)("floating_button_settings")), 1),
        createBaseVNode("div", _hoisted_3$1, [
          createBaseVNode("label", _hoisted_4$1, toDisplayString(unref(t)("floating_position")), 1),
          createBaseVNode("div", _hoisted_5$1, [
            createBaseVNode("label", _hoisted_6$1, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => __props.form.floating_position = $event),
                type: "radio",
                value: "bottom-right"
              }, null, 512), [
                [vModelRadio, __props.form.floating_position]
              ]),
              createTextVNode(" " + toDisplayString(unref(t)("bottom_right")), 1)
            ]),
            createBaseVNode("label", _hoisted_7$1, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => __props.form.floating_position = $event),
                type: "radio",
                value: "bottom-left"
              }, null, 512), [
                [vModelRadio, __props.form.floating_position]
              ]),
              createTextVNode(" " + toDisplayString(unref(t)("bottom_left")), 1)
            ]),
            createBaseVNode("label", _hoisted_8$1, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => __props.form.floating_position = $event),
                type: "radio",
                value: "top-right"
              }, null, 512), [
                [vModelRadio, __props.form.floating_position]
              ]),
              createTextVNode(" " + toDisplayString(unref(t)("top_right")), 1)
            ]),
            createBaseVNode("label", _hoisted_9$1, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[3] || (_cache[3] = ($event) => __props.form.floating_position = $event),
                type: "radio",
                value: "top-left"
              }, null, 512), [
                [vModelRadio, __props.form.floating_position]
              ]),
              createTextVNode(" " + toDisplayString(unref(t)("top_left")), 1)
            ])
          ])
        ]),
        createBaseVNode("div", _hoisted_10$1, [
          createBaseVNode("label", _hoisted_11$1, toDisplayString(unref(t)("floating_size")), 1),
          createBaseVNode("div", _hoisted_12$1, [
            createBaseVNode("div", _hoisted_13$1, [
              createBaseVNode("p", _hoisted_14$1, toDisplayString(unref(t)("mobile")), 1),
              createBaseVNode("div", _hoisted_15$1, [
                createBaseVNode("label", _hoisted_16$1, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => __props.form.floating_size_mobile = $event),
                    type: "radio",
                    value: "sm"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_mobile]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("small")), 1)
                ]),
                createBaseVNode("label", _hoisted_17$1, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[5] || (_cache[5] = ($event) => __props.form.floating_size_mobile = $event),
                    type: "radio",
                    value: "md"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_mobile]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("medium")), 1)
                ]),
                createBaseVNode("label", _hoisted_18$1, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => __props.form.floating_size_mobile = $event),
                    type: "radio",
                    value: "lg"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_mobile]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("large")), 1)
                ])
              ])
            ]),
            createBaseVNode("div", _hoisted_19$1, [
              createBaseVNode("p", _hoisted_20, toDisplayString(unref(t)("tablet")), 1),
              createBaseVNode("div", _hoisted_21, [
                createBaseVNode("label", _hoisted_22, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[7] || (_cache[7] = ($event) => __props.form.floating_size_tablet = $event),
                    type: "radio",
                    value: "sm"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_tablet]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("small")), 1)
                ]),
                createBaseVNode("label", _hoisted_23, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[8] || (_cache[8] = ($event) => __props.form.floating_size_tablet = $event),
                    type: "radio",
                    value: "md"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_tablet]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("medium")), 1)
                ]),
                createBaseVNode("label", _hoisted_24, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[9] || (_cache[9] = ($event) => __props.form.floating_size_tablet = $event),
                    type: "radio",
                    value: "lg"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_tablet]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("large")), 1)
                ])
              ])
            ]),
            createBaseVNode("div", _hoisted_25, [
              createBaseVNode("p", _hoisted_26, toDisplayString(unref(t)("desktop")), 1),
              createBaseVNode("div", _hoisted_27, [
                createBaseVNode("label", _hoisted_28, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[10] || (_cache[10] = ($event) => __props.form.floating_size_desktop = $event),
                    type: "radio",
                    value: "sm"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_desktop]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("small")), 1)
                ]),
                createBaseVNode("label", _hoisted_29, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[11] || (_cache[11] = ($event) => __props.form.floating_size_desktop = $event),
                    type: "radio",
                    value: "md"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_desktop]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("medium")), 1)
                ]),
                createBaseVNode("label", _hoisted_30, [
                  withDirectives(createBaseVNode("input", {
                    "onUpdate:modelValue": _cache[12] || (_cache[12] = ($event) => __props.form.floating_size_desktop = $event),
                    type: "radio",
                    value: "lg"
                  }, null, 512), [
                    [vModelRadio, __props.form.floating_size_desktop]
                  ]),
                  createTextVNode(" " + toDisplayString(unref(t)("large")), 1)
                ])
              ])
            ])
          ])
        ]),
        createBaseVNode("div", _hoisted_31, [
          createBaseVNode("label", _hoisted_32, toDisplayString(unref(t)("floating_custom_css")), 1),
          withDirectives(createBaseVNode("textarea", {
            "onUpdate:modelValue": _cache[13] || (_cache[13] = ($event) => __props.form.floating_custom_classes = $event),
            rows: "4",
            class: "cda-input"
          }, null, 512), [
            [vModelText, __props.form.floating_custom_classes]
          ]),
          createBaseVNode("p", _hoisted_33, toDisplayString(unref(t)("floating_custom_css_help")), 1)
        ]),
        createBaseVNode("div", _hoisted_34, [
          createBaseVNode("div", _hoisted_35, [
            createBaseVNode("label", _hoisted_36, toDisplayString(unref(t)("floating_label_login")), 1),
            withDirectives(createBaseVNode("input", {
              "onUpdate:modelValue": _cache[14] || (_cache[14] = ($event) => __props.form.floating_label_login = $event),
              type: "text",
              class: "cda-input"
            }, null, 512), [
              [vModelText, __props.form.floating_label_login]
            ])
          ]),
          createBaseVNode("div", _hoisted_37, [
            createBaseVNode("label", _hoisted_38, toDisplayString(unref(t)("floating_label_logout")), 1),
            withDirectives(createBaseVNode("input", {
              "onUpdate:modelValue": _cache[15] || (_cache[15] = ($event) => __props.form.floating_label_logout = $event),
              type: "text",
              class: "cda-input"
            }, null, 512), [
              [vModelText, __props.form.floating_label_logout]
            ])
          ])
        ]),
        createBaseVNode("div", _hoisted_39, [
          createBaseVNode("label", _hoisted_40, toDisplayString(unref(t)("floating_label_visibility")), 1),
          createBaseVNode("div", _hoisted_41, [
            createBaseVNode("label", _hoisted_42, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[16] || (_cache[16] = ($event) => __props.form.show_label_mobile = $event),
                type: "checkbox"
              }, null, 512), [
                [vModelCheckbox, __props.form.show_label_mobile]
              ]),
              createTextVNode(" " + toDisplayString(unref(t)("show_label_mobile")), 1)
            ]),
            createBaseVNode("label", _hoisted_43, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[17] || (_cache[17] = ($event) => __props.form.show_label_tablet = $event),
                type: "checkbox"
              }, null, 512), [
                [vModelCheckbox, __props.form.show_label_tablet]
              ]),
              createTextVNode(" " + toDisplayString(unref(t)("show_label_tablet")), 1)
            ]),
            createBaseVNode("label", _hoisted_44, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[18] || (_cache[18] = ($event) => __props.form.show_label_desktop = $event),
                type: "checkbox"
              }, null, 512), [
                [vModelCheckbox, __props.form.show_label_desktop]
              ]),
              createTextVNode(" " + toDisplayString(unref(t)("show_label_desktop")), 1)
            ])
          ])
        ])
      ]);
    };
  }
};
const _hoisted_1$1 = { class: "cda-admin-card cda-settings-form" };
const _hoisted_2 = { class: "cda-title" };
const _hoisted_3 = { class: "cda-toggle-row" };
const _hoisted_4 = { class: "cda-toggle-row-left" };
const _hoisted_5 = { class: "cda-label" };
const _hoisted_6 = { class: "cda-field-group" };
const _hoisted_7 = { class: "cda-label" };
const _hoisted_8 = { class: "cda-field-group" };
const _hoisted_9 = { class: "cda-label" };
const _hoisted_10 = { class: "cda-subtitle" };
const _hoisted_11 = { class: "cda-settings-grid" };
const _hoisted_12 = { class: "cda-label" };
const _hoisted_13 = { class: "cda-label" };
const _hoisted_14 = { class: "cda-label" };
const _hoisted_15 = { class: "cda-label" };
const _hoisted_16 = { class: "cda-settings-actions" };
const _hoisted_17 = ["disabled"];
const _hoisted_18 = {
  key: 0,
  class: "cda-save-success"
};
const _hoisted_19 = {
  key: 1,
  class: "cda-save-error"
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
          createBaseVNode("div", _hoisted_4, [
            createBaseVNode("span", _hoisted_5, toDisplayString(unref(t)("enable_modal")), 1),
            createBaseVNode("span", {
              class: normalizeClass(["cda-toggle-state", form.enable_modal ? "is-on" : "is-off"])
            }, toDisplayString(form.enable_modal ? "Actif" : "Inactif"), 3)
          ]),
          createVNode(_sfc_main$3, {
            modelValue: form.enable_modal,
            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => form.enable_modal = $event)
          }, null, 8, ["modelValue"])
        ]),
        createBaseVNode("div", _hoisted_6, [
          createBaseVNode("label", _hoisted_7, toDisplayString(unref(t)("redirect_after_login")), 1),
          withDirectives(createBaseVNode("input", {
            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => form.redirect_after_login = $event),
            type: "text",
            class: "cda-input"
          }, null, 512), [
            [vModelText, form.redirect_after_login]
          ])
        ]),
        createBaseVNode("div", _hoisted_8, [
          createBaseVNode("label", _hoisted_9, toDisplayString(unref(t)("redirect_after_logout")), 1),
          withDirectives(createBaseVNode("input", {
            "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => form.redirect_after_logout = $event),
            type: "text",
            class: "cda-input"
          }, null, 512), [
            [vModelText, form.redirect_after_logout]
          ])
        ]),
        _cache[7] || (_cache[7] = createBaseVNode("div", { class: "cda-divider" }, null, -1)),
        createBaseVNode("h3", _hoisted_10, toDisplayString(unref(t)("security")), 1),
        createBaseVNode("div", _hoisted_11, [
          createBaseVNode("div", null, [
            createBaseVNode("label", _hoisted_12, toDisplayString(unref(t)("max_login_attempts")), 1),
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
            createBaseVNode("label", _hoisted_13, toDisplayString(unref(t)("lock_time_seconds")), 1),
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
            createBaseVNode("label", _hoisted_14, toDisplayString(unref(t)("rest_max_requests")), 1),
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
            createBaseVNode("label", _hoisted_15, toDisplayString(unref(t)("rest_window_seconds")), 1),
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
        createVNode(_sfc_main$2, { form }, null, 8, ["form"]),
        createBaseVNode("div", _hoisted_16, [
          createBaseVNode("button", {
            class: "cda-button",
            disabled: loading.value,
            onClick: save
          }, toDisplayString(loading.value ? unref(t)("saving") : unref(t)("save")), 9, _hoisted_17),
          saved.value ? (openBlock(), createElementBlock("span", _hoisted_18, toDisplayString(unref(t)("saved")), 1)) : createCommentVNode("", true),
          error.value ? (openBlock(), createElementBlock("span", _hoisted_19, toDisplayString(unref(t)("error")), 1)) : createCommentVNode("", true)
        ])
      ]);
    };
  }
};
const _hoisted_1 = { class: "cda-admin-page" };
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
//# sourceMappingURL=admin-DUll-z8V.js.map
