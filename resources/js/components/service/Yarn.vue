<template>
  <div class="container mt-4">
    <h2 class="mb-4">Yarn Management</h2>

    <!-- Modal Trigger Button -->
    <button type="button" class="btn btn-primary mb-3" @click="openAddModal">
      Add Yarn
    </button>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="yarnModal" tabindex="-1" role="dialog" aria-labelledby="yarnModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="yarnModalLabel">{{ editingId ? 'Edit Yarn' : 'Add Yarn' }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="yarnName">Yarn Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  id="yarnName"
                  placeholder="Enter Yarn name"
                  required
                />
              </div>
              <div class="form-group">
                <label for="yarnCount">Yarn Count</label>
                <input
                  v-model="form.count"
                  type="text"
                  class="form-control"
                  id="yarnCount"
                  placeholder="e.g., 20s, 30/1"
                />
              </div>
              <div class="form-group">
                <label for="yarnType">Yarn Type</label>
                <input
                  v-model="form.type"
                  type="text"
                  class="form-control"
                  id="yarnType"
                  placeholder="e.g., Cotton, Polyester, Blend"
                />
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Discard</button>
            <button type="button" class="btn btn-primary" @click="submitForm">
              {{ editingId ? 'Update' : 'Add' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Count</th>
            <th>Type</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="yarnList.length === 0">
            <td colspan="5" class="text-center">No yarns found.</td>
          </tr>
          <tr v-for="yarn in yarnList" :key="yarn.id">
            <td>{{ yarn.id }}</td>
            <td>{{ yarn.name }}</td>
            <td>{{ yarn.count }}</td>
            <td>{{ yarn.type }}</td>
            <td>
              <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(yarn)">
                <i class="fas fa-edit"></i> Edit
              </button>
              <button class="btn btn-sm btn-danger" @click="deleteYarn(yarn.id)">
                <i class="fas fa-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
  
      <vue-awesome-paginate
        :total-items="totalItems"
        :items-per-page="itemsPerPage"
        :max-pages-shown="2"
        v-model="currentPage"
        :on-click="onClickHandler"
      />
   
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Axistance from '../../Axistance';
import 'vue-awesome-paginate/dist/style.css'; // Import the styles
import VueAwesomePaginate from 'vue-awesome-paginate'; // Import the component

const yarnList = ref([]);
const form = ref({ name: '', count: '', type: '' });
const editingId = ref(null);
const showModal = ref(false); // To control modal visibility programmatically if needed

// Pagination state
const currentPage = ref(1);
const totalItems = ref(0);
const itemsPerPage = ref(3); // Default, can be made dynamic
const API_PATH = 'yarn-count'; // Define your API path prefix

const fetchYarns = async (page = 1) => {
  try {
    const res = await Axistance.get(`${API_PATH}?page=${page}&per_page=${itemsPerPage.value}`);
    yarnList.value = res.data.data || []; // Ensure it's an array
    totalItems.value = res.data.total || 0;
    currentPage.value = res.data.current_page || 1;
    // Preserve client-side itemsPerPage if backend doesn't specify, or if it differs.
    // Ideally, backend should always return the per_page it used.
    itemsPerPage.value = res.data.per_page || itemsPerPage.value;
  } catch (error) {
    console.error('Error fetching yarns:', error.response ? error.response.data : error.message);
    yarnList.value = []; // Reset on error
    totalItems.value = 0;
    // Handle error (e.g., show a notification to the user)
  }
};

const resetForm = () => {
  form.value = { name: '', count: '', type: '' };
  editingId.value = null;
};

const openAddModal = () => {
  resetForm();
  $('#yarnModal').modal('show');
};

const openEditModal = (yarn) => {
  form.value.name = yarn.name;
  form.value.count = yarn.count || '';
  form.value.type = yarn.type || '';
  editingId.value = yarn.id;
  $('#yarnModal').modal('show');
};

const closeModal = () => {
  resetForm();
  $('#yarnModal').modal('hide');
};

const submitForm = async () => {
  try {
    if (editingId.value) {
      await Axistance.put(`${API_PATH}/${editingId.value}`, form.value);
      // Add success notification
    } else {
      await Axistance.post(API_PATH, form.value);
      // Add success notification
    }
    resetForm();
    fetchYarns(currentPage.value); // Fetch current page after add/edit
    $('#yarnModal').modal('hide');
  } catch (error) {
    console.error('Error submitting form:', error.response ? error.response.data : error.message);
    // Handle error (e.g., show validation errors to the user)
  }
};

const deleteYarn = async (id) => {
  if (confirm('Are you sure you want to delete this yarn?')) {
    try {
      await Axistance.delete(`${API_PATH}/${id}`);
      fetchYarns(currentPage.value); // Refresh list, consider if it should go to page 1 or stay
      // Add success notification
    } catch (error) {
      console.error('Error deleting yarn:', error);
      // Handle error
    }
  }
};

const onClickHandler = (page) => {
  fetchYarns(page);
};

onMounted(() => {
  fetchYarns(currentPage.value);
});

// Watch for currentPage changes if you want to fetch data when it's changed programmatically elsewhere
// watch(currentPage, (newPage) => {
//   fetchYarns(newPage);
// });

</script>

<style scoped>
.modal-footer .btn + .btn {
  margin-left: 0.5rem;
}
/* Add any additional styling you need */
.table-hover tbody tr:hover {
  background-color: #f5f5f5;
}
.btn-warning, .btn-danger {
  color: white; /* Ensure icon visibility if using dark icons */
}
</style>