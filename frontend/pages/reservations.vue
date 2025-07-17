<template>
  <div class="container mt-5">
    <h2>Mes Réservations</h2>
    <ul class="list-group" v-if="reservations.length">
      <li v-for="r in reservations" :key="r.id" class="list-group-item">
        Bureau {{ r.bureau_id }} – du {{ r.date_debut }} au {{ r.date_fin }}
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from '../stores/user'
import { useFetch } from '#app'
const store = useUserStore()

const { data: reservations } = await useFetch(
  `/api/users/${store.user?.id}/reservations`,
  {
    method: 'GET',
    headers: {
      Authorization: `Bearer ${store.token}`,
    },
  }
)
</script>
