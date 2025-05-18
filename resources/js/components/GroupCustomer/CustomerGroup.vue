<template>
    <div class="container mt-4">
    <h2 class="mb-4">Customer Groups</h2>

    <!-- Modal Trigger Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModalCenter">
      Add Group
    </button>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ editingId ? 'Edit Group' : 'Add Group' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="groupName">Group Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  id="groupName"
                  placeholder="Enter group name"
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
        <tr v-for="group in customerGroups" :key="group.id">
          <td>{{ group.id }}</td>
          <td>{{ group.name }}</td>
          <td>
            <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(group)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteGroup(group.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  
  const customerGroups = ref([])
  const form = ref({ name: '' })
  const editingId = ref(null)
  
  const fetchGroups = async () => {
    const res = await axios.get(baseUrl+'customer-groups')
    customerGroups.value = res.data
  }
  const openEditModal = (group) => {
    form.value.name = group.name
    editingId.value = group.id
    $('#exampleModalCenter').modal('show')
  }
  const submitForm = async () => {
    if (editingId.value) {
      await axios.put(baseUrl+`customer-groups/${editingId.value}`, form.value)
    } else {
      await axios.post(baseUrl+'customer-groups', form.value)
    }
  
    form.value.name = ''
    editingId.value = null
    fetchGroups()
    $('#exampleModalCenter').modal('hide')
  }
  
  const editGroup = (group) => {
    form.value.name = group.name
    editingId.value = group.id
    $('#exampleModalCenter').modal('show')
  }
  
  const deleteGroup = async (id) => {
    if (confirm('Are you sure?')) {
      await axios.delete(baseUrl+`customer-groups/${id}`)
      fetchGroups()
    }
  }
  
  onMounted(fetchGroups)
  </script>

<style scoped>
.modal-footer .btn + .btn {
  margin-left: 0.5rem;
}
</style>