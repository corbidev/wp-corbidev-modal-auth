var _a, _b;
import { c as computed, r as ref, u as useI18n, o as onMounted, a as openBlock, b as createElementBlock, t as toDisplayString, d as unref, e as createCommentVNode, f as createBaseVNode, w as withModifiers, g as withDirectives, v as vModelText, h as vModelCheckbox, i as createBlock, j as createApp } from "./tailwind-BJYhuhMy.js";
const BASE_URL = ((_a = window.CorbidevModalAuth) == null ? void 0 : _a.restUrl) || "";
const NONCE = ((_b = window.CorbidevModalAuth) == null ? void 0 : _b.nonce) || "";
async function request(endpoint, method = "GET", body = null) {
  const options = {
    method,
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      "X-WP-Nonce": NONCE
    }
  };
  if (body) {
    options.body = JSON.stringify(body);
  }
  const response = await fetch(`${BASE_URL}${endpoint}`, options);
  let data;
  try {
    data = await response.json();
  } catch (e) {
    throw {
      success: false,
      code: "invalid_json"
    };
  }
  if (!response.ok) {
    throw data;
  }
  return data;
}
async function login(payload) {
  return request("/login", "POST", {
    username: payload.username,
    password: payload.password,
    remember: payload.remember ?? false
  });
}
async function logout() {
  return request("/logout", "POST");
}
async function lostPassword(user_login) {
  return request("/lost-password", "POST", {
    user_login
  });
}
async function getCurrentUser() {
  return request("/me", "GET");
}
const user = ref(null);
const loading = ref(false);
const errorCode = ref(null);
const isLoggedIn = computed(() => !!user.value);
async function checkSession() {
  try {
    const response = await getCurrentUser();
    user.value = response.logged_in ? response : null;
  } catch {
    user.value = null;
  }
}
async function doLogin(payload) {
  loading.value = true;
  errorCode.value = null;
  try {
    const response = await login(payload);
    await checkSession();
    if (response.redirect) {
      window.location.href = response.redirect;
    }
    return response;
  } catch (error) {
    errorCode.value = error.code || "unknown_error";
    throw error;
  } finally {
    loading.value = false;
  }
}
async function doLogout() {
  loading.value = true;
  errorCode.value = null;
  try {
    const response = await logout();
    user.value = null;
    if (response.redirect) {
      window.location.href = response.redirect;
    }
    return response;
  } catch (error) {
    errorCode.value = error.code || "unknown_error";
    throw error;
  } finally {
    loading.value = false;
  }
}
async function doLostPassword(user_login) {
  loading.value = true;
  errorCode.value = null;
  try {
    const response = await lostPassword(user_login);
    return response;
  } catch (error) {
    errorCode.value = error.code || "unknown_error";
    throw error;
  } finally {
    loading.value = false;
  }
}
function useAuth() {
  return {
    user,
    loading,
    errorCode,
    isLoggedIn,
    checkSession,
    login: doLogin,
    logout: doLogout,
    lostPassword: doLostPassword
  };
}
const _hoisted_1 = ["disabled"];
const _hoisted_2 = {
  key: 2,
  class: "fixed inset-0 flex items-center justify-center bg-black/50 z-50"
};
const _hoisted_3 = { class: "relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8" };
const _hoisted_4 = { class: "text-2xl font-semibold text-center mb-6" };
const _hoisted_5 = {
  key: 0,
  class: "mb-4 text-sm text-center text-red-600"
};
const _hoisted_6 = ["placeholder"];
const _hoisted_7 = ["placeholder"];
const _hoisted_8 = {
  key: 0,
  class: "flex items-center gap-2 text-sm"
};
const _hoisted_9 = ["disabled"];
const _hoisted_10 = {
  key: 1,
  class: "text-center text-sm mt-2"
};
const _hoisted_11 = ["placeholder"];
const _hoisted_12 = ["disabled"];
const _hoisted_13 = { class: "text-center text-sm mt-2" };
const _sfc_main$1 = {
  __name: "LoginModal",
  setup(__props) {
    var _a2;
    const { t } = useI18n();
    const {
      isLoggedIn: isLoggedIn2,
      login: login2,
      logout: logout2,
      lostPassword: lostPassword2,
      loading: loading2,
      errorCode: errorCode2,
      checkSession: checkSession2
    } = useAuth();
    const settings = ((_a2 = window.CorbidevModalAuth) == null ? void 0 : _a2.settings) || {};
    const isOpen = ref(false);
    const mode = ref("login");
    const form = ref({
      username: "",
      password: "",
      remember: false
    });
    const lostEmail = ref("");
    const canRemember = computed(() => settings.enable_remember_me);
    const canLostPassword = computed(() => settings.enable_lost_password);
    const showLoginButton = computed(() => !isLoggedIn2.value);
    const showLogoutButton = computed(() => isLoggedIn2.value);
    function open() {
      isOpen.value = true;
    }
    function close() {
      isOpen.value = false;
    }
    async function submitLogin() {
      await login2({
        username: form.value.username,
        password: form.value.password,
        remember: form.value.remember
      });
    }
    async function submitLogout() {
      await logout2();
    }
    async function submitLostPassword() {
      await lostPassword2(lostEmail.value);
      mode.value = "login";
    }
    onMounted(async () => {
      await checkSession2();
      if (settings.auto_open && !isLoggedIn2.value) {
        isOpen.value = true;
      }
    });
    return (_ctx, _cache) => {
      return openBlock(), createElementBlock("div", null, [
        showLoginButton.value ? (openBlock(), createElementBlock("button", {
          key: 0,
          onClick: open,
          class: "px-4 py-2 rounded-xl bg-primary text-white hover:opacity-90 transition"
        }, toDisplayString(unref(t)("login")), 1)) : createCommentVNode("", true),
        showLogoutButton.value ? (openBlock(), createElementBlock("button", {
          key: 1,
          onClick: submitLogout,
          disabled: unref(loading2),
          class: "px-4 py-2 rounded-xl bg-gray-800 text-white hover:opacity-90 transition"
        }, toDisplayString(unref(t)("logout")), 9, _hoisted_1)) : createCommentVNode("", true),
        isOpen.value ? (openBlock(), createElementBlock("div", _hoisted_2, [
          createBaseVNode("div", _hoisted_3, [
            createBaseVNode("button", {
              onClick: close,
              class: "absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition"
            }, " ✕ "),
            createBaseVNode("h2", _hoisted_4, toDisplayString(mode.value === "login" ? unref(t)("login") : unref(t)("lost_password")), 1),
            unref(errorCode2) ? (openBlock(), createElementBlock("div", _hoisted_5, toDisplayString(unref(t)(unref(errorCode2))), 1)) : createCommentVNode("", true),
            mode.value === "login" ? (openBlock(), createElementBlock("form", {
              key: 1,
              onSubmit: withModifiers(submitLogin, ["prevent"]),
              class: "space-y-4"
            }, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[0] || (_cache[0] = ($event) => form.value.username = $event),
                type: "text",
                class: "w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary",
                placeholder: unref(t)("username")
              }, null, 8, _hoisted_6), [
                [vModelText, form.value.username]
              ]),
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[1] || (_cache[1] = ($event) => form.value.password = $event),
                type: "password",
                class: "w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary",
                placeholder: unref(t)("password")
              }, null, 8, _hoisted_7), [
                [vModelText, form.value.password]
              ]),
              canRemember.value ? (openBlock(), createElementBlock("div", _hoisted_8, [
                withDirectives(createBaseVNode("input", {
                  type: "checkbox",
                  "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => form.value.remember = $event)
                }, null, 512), [
                  [vModelCheckbox, form.value.remember]
                ]),
                createBaseVNode("span", null, toDisplayString(unref(t)("remember_me")), 1)
              ])) : createCommentVNode("", true),
              createBaseVNode("button", {
                type: "submit",
                disabled: unref(loading2),
                class: "w-full py-2 rounded-xl bg-primary text-white hover:opacity-90 transition"
              }, toDisplayString(unref(loading2) ? unref(t)("loading") : unref(t)("login")), 9, _hoisted_9),
              canLostPassword.value ? (openBlock(), createElementBlock("div", _hoisted_10, [
                createBaseVNode("button", {
                  type: "button",
                  onClick: _cache[3] || (_cache[3] = ($event) => mode.value = "lost"),
                  class: "text-primary hover:underline"
                }, toDisplayString(unref(t)("forgot_password")), 1)
              ])) : createCommentVNode("", true)
            ], 32)) : (openBlock(), createElementBlock("form", {
              key: 2,
              onSubmit: withModifiers(submitLostPassword, ["prevent"]),
              class: "space-y-4"
            }, [
              withDirectives(createBaseVNode("input", {
                "onUpdate:modelValue": _cache[4] || (_cache[4] = ($event) => lostEmail.value = $event),
                type: "email",
                class: "w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary",
                placeholder: unref(t)("email")
              }, null, 8, _hoisted_11), [
                [vModelText, lostEmail.value]
              ]),
              createBaseVNode("button", {
                type: "submit",
                disabled: unref(loading2),
                class: "w-full py-2 rounded-xl bg-primary text-white hover:opacity-90 transition"
              }, toDisplayString(unref(loading2) ? unref(t)("loading") : unref(t)("reset_password")), 9, _hoisted_12),
              createBaseVNode("div", _hoisted_13, [
                createBaseVNode("button", {
                  type: "button",
                  onClick: _cache[5] || (_cache[5] = ($event) => mode.value = "login"),
                  class: "text-primary hover:underline"
                }, toDisplayString(unref(t)("back_to_login")), 1)
              ])
            ], 32))
          ])
        ])) : createCommentVNode("", true)
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
//# sourceMappingURL=app-CZLj-q4-.js.map
