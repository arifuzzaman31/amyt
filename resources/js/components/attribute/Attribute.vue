<template>
  <div class="container mt-4">
    

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="attributeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ editingId ? 'Edit Attribute' : 'Add Attribute' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="submitForm">
            <div class="modal-body">
              <div class="statbox widget box-shadow">
                <div class="widget-header">
                  <div class="form-group">
                    <label for="AttributeType">Attribute Type</label>
                    <select v-model="form.type" class="form-control" id="AttributeType">
                      <option disabled value="">Select a Type</option>
                      <option value="weight">weight</option>
                      <option value="color">color</option>
                      <option value="size">size</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="AttributeName">Attribute Name</label>
                    <input v-model="form.name" type="text" class="form-control" id="AttributeName"
                      placeholder="Enter Attribute name" />
                  </div>
                  <div class="form-group">
                    <label for="AttributeGroup">Group</label>
                    <select v-model="form.group" class="form-control" id="AttributeGroup">
                      <option disabled value="">Select a Type</option>
                      <option value="product">product</option>
                      <!-- <option value="user">user</option> -->
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="AttributeStatus">Status</label>
                    <select v-model="form.is_active" class="form-control" id="AttributeStatus">
                      <option value="1">Active</option>
                      <option value="0">Deactive</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Discard</button>
              <button type="button" class="btn btn-primary" @click="submitForm">
                {{ editingId ? 'Update' : 'Add' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="statbox widget box-shadow">
      <div class="widget-header">
        <h4>Attribute List</h4>
      </div>
      <div class="widget-content widget-content-area">

    <!-- Modal Trigger Button -->
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#attributeModal">
      Add Attribute
    </button>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Type</th>
          <th>Group</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="attr in attributeList" :key="attr.id">
          <td>{{ attr.id }}</td>
          <td>{{ attr.name }}</td>
          <td>{{ attr.type }}</td>
          <td>{{ attr.group }}</td>
          <td>{{ attr.is_active }}</td>
          <td>
            <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(attr)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteAttr(attr.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </div>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Axistance from '../../Axistance'

const attributeList = ref([])
const form = ref({
  name: '',
  type: '',
  group: 'product',
  is_active: 1
})
const editingId = ref(null)

const fetchAttribute = async () => {
  const res = await Axistance.get('attribute')
  if (res.status !== 200) {
    alert('Failed to fetch attributes')
    return
  }
  attributeList.value = res.data
}
const openEditModal = (attr) => {
  form.value.name = attr.name
  form.value.type = attr.type
  form.value.group = attr.group
  form.value.is_active = attr.is_active
  editingId.value = attr.id
  $('#attributeModal').modal('show')
}
const submitForm = async () => {
  try {
    let resp;
    if (editingId.value) {
      resp = await Axistance.put(`attribute/${editingId.value}`, form.value)
    } else {
      resp = await Axistance.post('attribute', form.value)
    }
    notifier({ status: "success", message: resp.data.message || "Operation successful" });
    form.value.name = ''
    form.value.type = ''
    form.value.group = 'product'
    form.value.is_active = 1
    editingId.value = null
    fetchAttribute()
    $('#attributeModal').modal('hide')
  } catch (error) {
    alert(error.response?.data?.message || 'An error occurred')
  }
}

const editGroup = (group) => {
  form.value.name = group.name
  editingId.value = group.id
  $('#attributeModal').modal('show')
}

const deleteAttr = async (id) => {
  if (confirm('Are you sure?')) {
    await Axistance.delete(`attribute/${id}`)
    fetchAttribute()
  }
}

onMounted(fetchAttribute)
</script>

<style scoped>
.modal-footer .btn+.btn {
  margin-left: 0.5rem;
}
</style>