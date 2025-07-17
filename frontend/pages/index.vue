<template>
    <div class="container mt-5">
      <h2>Connexion</h2>
  
      <Alert v-if="message" :message="message" type="danger" />
  
      <form @submit.prevent="login">
        <div class="mb-3">
          <label>Email</label>
          <input v-model="email" type="email" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Mot de passe</label>
          <input v-model="password" type="password" class="form-control" />
        </div>
        <button class="btn btn-primary">Se connecter</button>
      </form>
    </div>
</template>
  
<script setup lang="ts">
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { useUserStore } from '../stores/user'
  import Alert from '../components/Alert.vue'
  import { useFetch, useRuntimeConfig } from '#app'
  const store = useUserStore()
  const email = ref('')
  const password = ref('')
  const router = useRouter()
  const message = ref('')
  
  async function login() {
    const { data, error } = await useFetch('${config.public.apiBase}/login', {
      method: 'POST',
      body: { email: email.value, password: password.value },
    })
  
    if (data.value) {
      store.setUser(data.value)
      router.push('/reservations')
    } else {
      message.value = 'Erreur de connexion. Veuillez vérifier vos identifiants.'
    }
  }
  </script>
  