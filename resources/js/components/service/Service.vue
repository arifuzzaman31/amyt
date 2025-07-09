<template>
  <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
    <div class="widget-header">
      <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
          <h4>Service</h4>
          <a :href="url + 'create-service'" class="btn btn-primary mb-3">
            Add Service
          </a>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="widget-content widget-content-area">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="">#</th>
              <th>Invoice No</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(service, ind) in serviceList" :key="service.id">
              <td>{{ ind++ }}</td>
              <td>{{ service.invoice_no }}</td>
              <td>{{ service.service_date }}</td>
              <td>{{ service.customer?.name }}</td>
              <td>
                <span :class="getStatusClass(service.status)">
                  {{ getStatusLabel(service.status) }}
                </span>
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(service)">Edit</button>
                <button class="btn btn-sm btn-danger" @click="deleteService(service.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <EditService v-if="selectedService" :service="selectedService" @service-updated="handleServiceUpdated" @close-modal="closeEditModal"/>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Axistance from '../../Axistance'
import EditService from './EditService.vue'

const serviceList = ref([])
const form = ref({ name: '' })
const editingId = ref(null)
const selectedService = ref(null)
const url = ref(baseUrl)

const fetchServices = async () => {
  const res = await Axistance.get('service')
  serviceList.value = res.data
}
const openEditModal = (service) => {
    selectedService.value = service;
}
const submitForm = async () => {
  if (editingId.value) {
    await Axistance.put(`service/${editingId.value}`, form.value)
  } else {
    await Axistance.post('service', form.value)
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
    await Axistance.delete(`service/${id}`)
    fetchServices()
  }
}
const getStatusClass = (status) => {
  const classMap = {
    0: 'badge badge-light-warning',
    1: 'badge badge-light-success',
    2: 'badge badge-light-danger',
    3: 'badge badge-light-info',
    4: 'badge badge-light-primary'
  };
  return classMap[status] || 'badge';
}
const getStatusLabel = (status) => {
  const statusMap = {
    0: 'Pending',
    1: 'Approved',
    2: 'Rejected',
    3: 'Draft',
    4: 'Closed'
  };
  return statusMap[status] || 'Unknown';
}
const closeEditModal = () => {
    selectedService.value = null;
}
const handleServiceUpdated = () => {
    fetchServices();
    closeEditModal();
}
onMounted(fetchServices)
</script>

<style scoped>
.status-cell {
  width: 20%;
  white-space: nowrap;
}

.badge {
  border-radius: 15px;
  font-size: 12px;
  font-weight: 600;
  padding: 5px 10px;
  text-transform: capitalize;
}

.badge-light-warning {
  background-color: rgba(226, 160, 63, 0.1);
  color: #e2a03f;
}

.badge-light-success {
  background-color: rgba(26, 188, 156, 0.1);
  color: #1abc9c;
}

.badge-light-danger {
  background-color: rgba(231, 81, 90, 0.1);
  color: #e7515a;
}

.badge-light-info {
  background-color: rgba(33, 150, 243, 0.1);
  color: #2196f3;
}

.badge-light-primary {
  background-color: rgba(67, 97, 238, 0.1);
  color: #4361ee;
}
</style>