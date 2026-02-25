export function useI18n() {

  const translations =
    window.CorbidevModalAuth?.i18n ??
    window.CDA_CONFIG?.translations ??
    {}

  const t = (key) => {
    return translations[key] ?? key
  }

  return { t }
}