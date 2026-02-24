import { o as openBlock, c as createElementBlock, a as createBaseVNode, t as toDisplayString, u as unref, w as withDirectives, v as vModelText, b as createCommentVNode, r as ref, d as createBlock, e as createApp } from "./tailwind-DbXb10vP.js";
function useI18n() {
  var _a;
  const translations = ((_a = window.CDA_CONFIG) == null ? void 0 : _a.translations) ?? {};
  const t = (key) => {
    return translations[key] ?? key;
  };
  return { t };
}
async function login(username, password) {
  const response = await fetch(window.CDA_CONFIG.rest_url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-WP-Nonce": window.CDA_CONFIG.nonce
    },
    body: JSON.stringify({
      username,
      password
    })
  });
  const data = await response.json();
  return data;
}
const _hoisted_1 = { class: "cda-modal" };
const _hoisted_2 = { class: "cda-card" };
const _hoisted_3 = { class: "cda-title" };
const _hoisted_4 = ["placeholder"];
const _hoisted_5 = ["placeholder"];
const _hoisted_6 = {
  key: 0,
  class: "cda-error"
};
const _sfc_main$1 = {
  __name: "LoginModal",
  setup(__props) {
    const { t } = useI18n();
    const username = ref("");
    const password = ref("");
    const error = ref(null);
    async function submit() {
      const res = await login(username.value, password.value);
      if (!res.success) {
        error.value = res.message;
      } else {
        window.location.reload();
      }
    }
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", _hoisted_1, [
        createBaseVNode("div", _hoisted_2, [
          createBaseVNode("h2", _hoisted_3, toDisplayString(unref(t)("login")), 1),
          withDirectives(createBaseVNode("input", {
            "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => username.value = $event),
            class: "cda-input",
            placeholder: unref(t)("username")
          }, null, 8, _hoisted_4), [
            [vModelText, username.value]
          ]),
          withDirectives(createBaseVNode("input", {
            "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => password.value = $event),
            type: "password",
            class: "cda-input",
            placeholder: unref(t)("password")
          }, null, 8, _hoisted_5), [
            [vModelText, password.value]
          ]),
          createBaseVNode("button", {
            class: "cda-button",
            onClick: submit
          }, toDisplayString(unref(t)("login")), 1),
          error.value ? (openBlock(), createElementBlock("p", _hoisted_6, toDisplayString(error.value), 1)) : createCommentVNode("", true)
        ])
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
createApp(_sfc_main).mount("#cda-app");
//# sourceMappingURL=app-CDE2VsQi.js.map
