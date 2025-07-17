import { defineStore } from 'pinia'
import {ref} from 'vue'

interface LoginResponse{
  access_token: string
  user: {
    id: number
  }
}

export const useAuthStore = defineStore('auth', () =>{
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase
  const token = ref<string | null>(null)
  const user = ref<any>(null)
  const login = async (credentials: {email: string; password: string}) =>{
    try {
      const response = await $fetch<LoginResponse>(`${apiBase}/login`,{
        method: 'POST',
        body: credentials
        
      })
      
      if(response && response.access_token){
        token.value = response.access_token
        user.value = response.user
      }
      else{
        console.error('Aucun token trouvéd dans la reponse')
      }

      useCookie('token').value = response.access_token
    } catch (error) {
      console.error("erreur de connexion", error)
    }
    
  }
  const logout = () =>{
    token.value = null
    user.value = null
    useCookie('token').value = null
  }
  return {token, user, login, logout}
})

export default useAuthStore
