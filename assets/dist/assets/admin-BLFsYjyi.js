import { o as openBlock, c as createElementBlock, a as createBaseVNode, n as normalizeClass, f as reactive, g as createVNode, d as createBlock, e as createApp } from "./tailwind-DbXb10vP.js";
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
const _hoisted_1 = { class: "cda-admin-card" };
const _sfc_main$1 = {
  __name: "SettingsForm",
  setup(__props) {
    const form = reactive(window.CDA_ADMIN.settings);
    function save() {
      fetch("/wp-json/corbidev/v1/settings", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": window.CDA_ADMIN.nonce
        },
        body: JSON.stringify(form)
      });
    }
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        _cache[1] || (_cache[1] = createBaseVNode("h2", { class: "cda-title" }, "Modal Settings", -1)),
        createVNode(_sfc_main$2, {
          modelValue: form.enabled,
          "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => form.enabled = $event)
        }, null, 8, ["modelValue"]),
        createBaseVNode("button", {
          class: "cda-button",
          onClick: save
        }, " Save ")
      ]);
    };
  }
};
const _sfc_main = {
  __name: "App",
  setup(__props) {
    return (_ctx, _cache) => {
      return openBlock(), createBlock(_sfc_main$1);
    };
  }
};
createApp(_sfc_main).mount("#cda-admin-app");
//# sourceMappingURL=admin-BLFsYjyi.js.map
