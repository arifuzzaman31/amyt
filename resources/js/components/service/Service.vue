<template>
    <div class="container mt-4">
    <h2 class="mb-4">Service</h2>

    <!-- Modal Trigger Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#serviceModal">
      Add Service
    </button>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ editingId ? 'Edit Service' : 'Add Service' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="serviceName">Service Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  id="serviceName"
                  placeholder="Enter Service name"
                />
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Discard</button>
            <button type="button" class="btn btn-primary" @click="submitForm">
              {{ editingId ? 'Update' : 'Add' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="service in serviceList" :key="service.id">
          <td>{{ service.id }}</td>
          <td>{{ service.name }}</td>
          <td>
            <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(service)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteService(service.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const serviceList = ref([])
  const form = ref({ name: '' })
  const editingId = ref(null)
  
  const fetchServices = async () => {
    const res = await axios.get(baseUrl+'service')
    serviceList.value = res.data
  }
  const openEditModal = (group) => {
    form.value.name = group.name
    editingId.value = group.id
    $('#serviceModal').modal('show')
  }
  const submitForm = async () => {
    if (editingId.value) {
      await axios.put(baseUrl+`service/${editingId.value}`, form.value)
    } else {
      await axios.post(baseUrl+'service', form.value)
    }
  
    form.value.name = ''
    editingId.value = null
    fetchServices()
    $('#serviceModal').modal('hide')
  }
  
  const editGroup = (service) => {
    form.value.name = service.name
    editingId.value = service.id
    $('#serviceModal').modal('show')
  }
  
  const deleteService = async (id) => {
    if (confirm('Are you sure?')) {
      await axios.delete(baseUrl+`service/${id}`)
      fetchServices()
    }
  }
  
  onMounted(fetchServices)
  </script>

<style scoped>
.modal-footer .btn + .btn {
  margin-left: 0.5rem;
}
</style>