<template>
    <div class="container mt-4">
    <h2 class="mb-4">Yarn List</h2>

    <!-- Modal Trigger Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#yarnModal">
      Add Yarn
    </button>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="yarnModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <tr v-for="yarn in yarnList" :key="yarn.id">
          <td>{{ yarn.id }}</td>
          <td>{{ yarn.name }}</td>
          <td>
            <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(yarn)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteYarn(yarn.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const yarnList = ref([])
  const form = ref({ name: '' })
  const editingId = ref(null)
  
  const fetchYarns = async () => {
    const res = await axios.get(baseUrl+'yarn-count')
    yarnList.value = res.data
  }
  const openEditModal = (group) => {
    form.value.name = group.name
    editingId.value = group.id
    $('#yarnModal').modal('show')
  }
  const submitForm = async () => {
    if (editingId.value) {
      await axios.put(baseUrl+`yarn-count/${editingId.value}`, form.value)
    } else {
      await axios.post(baseUrl+'yarn-count', form.value)
    }
  
    form.value.name = ''
    editingId.value = null
    fetchYarns()
    $('#yarnModal').modal('hide')
  }
  
  const editYarn = (service) => {
    form.value.name = service.name
    editingId.value = service.id
    $('#yarnModal').modal('show')
  }
  
  const deleteYarn = async (id) => {
    if (confirm('Are you sure?')) {
      await axios.delete(baseUrl+`yarn-count/${id}`)
      fetchYarns()
    }
  }
  
  onMounted(fetchYarns)
  </script>

<style scoped>
.modal-footer .btn + .btn {
  margin-left: 0.5rem;
}
</style>