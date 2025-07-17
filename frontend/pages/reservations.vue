<template>
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Créer Réservation</h2>  
        <button class="btn btn-danger" @click="logout">Deconnexion</button>
      </div>

      <Alert v-if="successMessage" :message="successMessage" type="success" />
      <Alert v-if="errorMessage" :message="errorMessage" type="danger" />
  
      <form @submit.prevent="createReservation">
        <div class="mb-3">
          <label>Bureau</label>
          <select v-model="bureau_id" class="form-select">
            <option v-for="bureau in bureaus" :key="bureau.id" :value="bureau.id">{{ bureau.nom }} - {{bureau.emplacement }}</option>
          </select>
          <small class="text-danger" v-if="fieldErrors.bureau_id">{{ fieldErrors.bureau_id }}</small>
        </div>
        <div class="mb-3">
          <label>Date début</label>
          <input v-model="date_debut" type="datetime-local" class="form-control" />
          <small class="text-danger" v-if="fieldErrors.date_debut">{{ fieldErrors.date_debut }}</small>
        </div>
        <div class="mb-3">
          <label>Date fin</label>
          <input v-model="date_fin" type="datetime-local" class="form-control" />
          <small class="text-danger" v-if="fieldErrors.date_fin">{{ fieldErrors.date_fin }}</small>
        </div>
        <button class="btn btn-success">Réserver</button>
      </form>
      <div class = "mt-5">
            <h2>Liste des réservations</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bureau</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                        </tr>
                    </thead>     
                    <tbody>
                      <tr v-for="reservation in reservations" :key="reservation.id">
                        <td>{{ reservation.id }}</td>
                        <td>{{ reservation.bureau.nom }}</td>
                        <td>{{ reservation.date_debut }}</td>
                        <td>{{ reservation.date_fin }}</td>
                      </tr>
                    </tbody>   
                </table>
            </div>

        </div>
    </div>
</template>
  
<script setup lang="ts">
  
  definePageMeta({
    middleware: ['auth']
  })
  import { ref, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  import { useAuthStore } from '../stores/auth'
  import Alert from '../components/Alert'
  
  const router = useRouter()
  const authStore = useAuthStore()
  const bureaus = ref({})
  const reservations = ref()
  const bureau_id = ref('')
  const date_debut = ref('')
  const date_fin = ref('')
  const successMessage = ref('')
  const errorMessage = ref('')
  const currentPage = ref(1)
  const lastPage = ref(1)
  const links = ref([])
  const config = useRuntimeConfig()
  const fieldErrors = ref<{ bureau_id?: string; date_debut?: string; date_fin?: string }>({})
  interface BureauResponse{
    nom: string
  }

  interface Bureau {
    nom: string
    emplacement: string
  }
  
  interface Reservation {
    id: number
    bureau: Bureau
    date_debut: string
    date_fin: string,
  
  }

  onMounted(()=>{
    fetchBureaus()
    fetchReservations()
  })

  const fetchBureaus = async ()=>{
    try{
       
        const response = await $fetch<BureauResponse>(`${config.public.apiBase}/bureaus`)
        bureaus.value = response
        
    }
    catch(error){
        console.error('erreur lors de la recuperation des bureaux', error)
    }
  }

  const fetchReservations = async (page = 1) => {
    try {
      const response = await $fetch<Reservation>( `${config.public.apiBase}/users/${authStore.user.id}/reservations`, {
          headers: {
            Authorization: `Bearer ${authStore.token}`,
          },
        }
      )
      reservations.value = response
    } catch (error) {
      console.error('Erreur lors de la récupération des réservations', error)
    }
  }
  const goToPage = (page: number) => {
    if (page >= 1 && page <= lastPage.value) {
      fetchReservations(page)
    }
  }
    
  const createReservation = async () =>{

    fieldErrors.value = {}
    errorMessage.value = ''
    successMessage.value = ''

    if (!bureau_id.value) {
      fieldErrors.value.bureau_id = 'Veuillez sélectionner un bureau.'
    }
    if (!date_debut.value) {
      fieldErrors.value.date_debut = 'Veuillez entrer la date de début.'
    }
    if (!date_fin.value) {
      fieldErrors.value.date_fin = 'Veuillez entrer la date de fin.'
    }

    if (Object.keys(fieldErrors.value).length > 0) {
      return
    }
    try{
        if(!bureau_id.value || !date_debut.value || !date_fin.value){
            errorMessage.value = "Veuillez remplir tous les champs"
            return
        }
        const response = await $fetch<BureauResponse>(`${config.public.apiBase}/reservation`,{
            method: 'POST',
            headers: {
                Authorization: `Bearer ${authStore.token}`
            },
            body: {
                bureau_id: bureau_id.value,
                date_debut: date_debut.value,
                date_fin: date_fin.value

            }
            
        })
        successMessage.value = 'Réservation créée avec succès.'
        bureau_id.value = ''
        date_debut.value = ''
        date_fin.value = ''
        fieldErrors.value = {}
        errorMessage.value = ''
        fetchReservations?.()
    }
    catch(error){
        console.error('erreur lors de la creation de la reservation', error)
        errorMessage.value = 'Erreur lors de la création. Veuillez réessayer.'
    }
  }

  const logout = async () => {
    try {
      await authStore.logout()
      router.push('/')
    } catch (error) {
      console.error('Erreur lors de la déconnexion', error)
    }
  }
</script>
  