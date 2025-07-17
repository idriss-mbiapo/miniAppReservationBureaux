// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: true },
  modules: ['@pinia/nuxt'],
  runtimeConfig: {
    public: {
      apiBase: 'http://localhost:8000/api'
    }
  },
  css: ['bootstrap/dist/css/bootstrap.min.css'],
})
