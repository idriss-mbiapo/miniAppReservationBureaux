import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    token: '',
    user: null as null | { id: number; email: string },
  }),
  actions: {
    setUser(data: any) {
      this.token = data.token
      this.user = data.user
    },
  },
})
