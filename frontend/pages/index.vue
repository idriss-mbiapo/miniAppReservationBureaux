<template>
    <div class="container mt-5">
      <h2>Connexion</h2>
  
      <Alert v-if="successMessage" :message="successMessage" type="success" />
      <Alert v-if="errorMessage" :message="errorMessage" type="danger" />
  
      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label>Email</label>
          <input v-model="email" type="email" class="form-control" />
          <small class="text-danger" v-if="fieldErrors.email">{{ fieldErrors.email }}</small>
        </div>
        <div class="mb-3">
          <label>Mot de passe</label>
          <input v-model="password" type="password" class="form-control" />
          <small class="text-danger" v-if="fieldErrors.password">{{ fieldErrors.password }}</small>
        </div>
        <button class="btn btn-primary">Se connecter</button>
      </form>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const email = ref('')
const password = ref('')
const successMessage = ref('')
const errorMessage = ref('')
const fieldErrors = ref<{ email?: string; password?: string }>({})

const router = useRouter()
const authStore = useAuthStore()

const handleLogin = async () => {
  
  fieldErrors.value = {}
  errorMessage.value = ''
  successMessage.value = ''

  if (email.value.trim() === '') {
    fieldErrors.value.email = 'Veuillez entrer votre email.'
  }
  if (password.value.trim() === '') {
    fieldErrors.value.password = 'Veuillez entrer votre mot de passe.'
  }

  if (Object.keys(fieldErrors.value).length > 0) {
    return
  }

  try {
    await authStore.login({
      email: email.value,
      password: password.value
    })

    if (authStore.token) {
      successMessage.value = 'Connexion réussie.'
      router.push('/reservations')
    } else {
      errorMessage.value = 'Identifiants invalides.'
    }

  } catch (error) {
    console.error('Erreur login:', error)
    errorMessage.value = 'Erreur serveur. Veuillez réessayer.'
  }
}
</script>
  