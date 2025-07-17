<template>
    <div class="container mt-5">
      <h2>Nouvelle Réservation</h2>
  
      <Alert v-if="successMessage" :message="successMessage" type="success" />
      <Alert v-if="errorMessage" :message="errorMessage" type="danger" />
  
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label>Bureau ID</label>
          <input v-model="bureau_id" type="number" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Date début</label>
          <input v-model="date_debut" type="datetime-local" class="form-control" />
        </div>
        <div class="mb-3">
          <label>Date fin</label>
          <input v-model="date_fin" type="datetime-local" class="form-control" />
        </div>
        <button class="btn btn-success">Réserver</button>
      </form>
    </div>
</template>
  
<script setup lang="ts">
  import { ref } from 'vue'
  import { useUserStore } from '../stores/user'
  import { useFetch } from '#app'
  import Alert from '../components/Alert'
  
  const bureau_id = ref('')
  const date_debut = ref('')
  const date_fin = ref('')
  const successMessage = ref('')
  const errorMessage = ref('')
  const store = useUserStore()
  
  async function submit() {
    successMessage.value = ''
    errorMessage.value = ''
  
    try {
      const { error } = await useFetch('/api/reservation', {
        method: 'POST',
        body: {
          bureau_id: bureau_id.value,
          date_debut: date_debut.value,
          date_fin: date_fin.value,
        },
        headers: {
          Authorization: `Bearer ${store.token}`,
        },
      })
  
      if (error.value) {
        errorMessage.value = 'Erreur lors de la réservation.'
      } else {
        successMessage.value = 'Réservation envoyée avec succès !'
      }
    } catch (err) {
      errorMessage.value = 'Une erreur inattendue est survenue.'
    }
  }
</script>
  