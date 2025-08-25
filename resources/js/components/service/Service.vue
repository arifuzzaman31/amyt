<template>
  <div id="tableCaption" class="layout-spacing">

    <div class="widget-content widget-content-area">
      <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
          <h4>Service</h4>
          <a :href="url + 'create-service'" class="btn btn-primary mb-3">
            Add Challan
          </a>
        </div>
      </div>
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
            <tr v-for="(service, ind) in serviceList.data" :key="ind">
              <td>{{ ((currentPage-1)*itemsPerPage)+ ++ind }}</td>
              <td>{{ service.invoice_no }}</td>
              <td>{{ formatDate(service.service_date) }}</td>
              <td>{{ service.customer?.name }}</td>
              <td>
                <span :class="getStatusClass(service.status)">
                  {{ getStatusLabel(service.status) }}
                </span>
              </td>
              <td class="text-center">
                <div class="dropdown custom-dropdown">
                      <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                      </a>

                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                          <!-- <a type="button" :class="'dropdown-item ' + (getStatusLabel(service.status) == 'Approved' ? 'disabled text-muted' : '')" @click="updateStatus(service.id)" href="javascript:void(0);">Approved</a> -->
                          <a type="button" class="dropdown-item" @click="makeInvoice(service)" href="javascript:void(0);">Make Invoice</a>
                          <a type="button" class="dropdown-item" @click="openEditModal(service)" href="javascript:void(0);">Edit</a>
                          <a type="button" class="dropdown-item" @click="deleteService(service.id)" href="javascript:void(0);">Delete</a>
                      </div>
                  </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <vue-awesome-paginate :total-items="serviceList.total" :items-per-page="itemsPerPage" :max-pages-shown="5"
                    v-model="currentPage" @click="onClickHandler" />
    </div>
    <EditService v-if="selectedService" :service="selectedService" @service-updated="handleServiceUpdated" @close-modal="closeEditModal"/>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Axistance from '../../Axistance'
import EditService from './EditService.vue'
import "vue-awesome-paginate/dist/style.css";
const serviceList = ref([])
const currentPage = ref(1)
const itemsPerPage = 10
const selectedService = ref(null)
const url = ref(baseUrl)

const fetchServices = async () => {
  const res = await Axistance.get('service', {
        params: {
            page: currentPage.value,
            per_page: itemsPerPage
        }
    })
  serviceList.value = res.data
}
const openEditModal = (service) => {
    fetchSingleService(service.id)
    // selectedService.value = service;
}
const fetchSingleService = async (id) => {
  const res = await Axistance.get(`service/${id}`)
  selectedService.value = res.data
}
const onClickHandler = (page) => {
    currentPage.value = page
}
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}
const updateStatus = (id) => {
  swal({
      title: 'Are you sure?',
      text: "Approve this service?!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '2em'
    }).then(async function(result) {
      if (result.value) {
        await Axistance.get(`service/${id}/approve`)
          .then(response => {
            swal(
              'Approved!',
              response.data.message,
              response.data.status
            )
            fetchServices()
        })
      }
    })
}

const deleteService = async (id) => {
  swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      padding: '2em'
    }).then(async function(result) {
      if (result.value) {
        await Axistance.delete(`service/${id}`)
          .then(response => {
            swal(
              'Deleted!',
              response.data.message,
              response.data.status
            )
            fetchServices()
        })
      }
    })
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
watch(currentPage, () => {
  fetchServices()
})
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