<template>
  <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
    <div class="container mt-4">
      <div class="statbox widget box box-shadow">
        <div class="widget-header">
          <h2 class="mb-4">Customer Groups</h2>

          <!-- Modal Trigger Button -->
          <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#customerGroupModal">
            Add Group
          </button>

          <!-- Bootstrap Modal -->
          <div class="modal fade" id="customerGroupModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                      <input v-model="form.name" type="text" class="form-control" id="groupName"
                        placeholder="Enter group name" />
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
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Axistance from '../../Axistance'

const customerGroups = ref([])
const form = ref({ name: '' })
const editingId = ref(null)

const fetchGroups = async () => {
  const res = await Axistance.get('customer-groups')
  customerGroups.value = res.data
}
const openEditModal = (group) => {
  form.value.name = group.name
  editingId.value = group.id
  $('#customerGroupModal').modal('show')
}
const submitForm = async () => {
  if (editingId.value) {
    await Axistance.put(`customer-groups/${editingId.value}`, form.value)
  } else {
    await Axistance.post('customer-groups', form.value)
  }

  form.value.name = ''
  editingId.value = null
  fetchGroups()
  $('#customerGroupModal').modal('hide')
}

const editGroup = (group) => {
  form.value.name = group.name
  editingId.value = group.id
  $('#customerGroupModal').modal('show')
}

const deleteGroup = async (id) => {
  swal({
    title: 'Are you sure?',
    text: "This Data wont be revert!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    padding: '2em'
  }).then(async function (result) {
    if (result.value) {
      await Axistance.delete(`customer-groups/${id}`)
        .then(response => {
          swal(
            'Deleted!',
            response.data.message,
            response.data.status
          )
          fetchGroups()
        })
    }
  })
}

onMounted(fetchGroups)
</script>

<style scoped>
.modal-footer .btn+.btn {
  margin-left: 0.5rem;
}
</style>