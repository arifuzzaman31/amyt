<template>
  <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
    <div class="container mt-4">
      <div class="statbox widget box box-shadow">
        <div class="widget-header">
          <h2 class="mb-4">Yarn Management</h2>

          <!-- Modal Trigger Button -->
          <button type="button" class="btn btn-primary mb-3" @click="openAddModal">
            Add Yarn
          </button>

          <!-- Bootstrap Modal -->
          <div class="modal fade" id="yarnModal" tabindex="-1" role="dialog" aria-labelledby="yarnModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="yarnModalLabel">{{ editingId ? 'Edit Yarn' : 'Add Yarn' }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body">
                  <div class="statbox widget box-shadow">
                  <div class="widget-header">
                  <form @submit.prevent="submitForm">
                    <div class="form-group">
                      <label for="yarnName">Yarn Name</label>
                      <input v-model="form.name" type="text" class="form-control" id="yarnName"
                        placeholder="Enter Yarn name" required />
                    </div>
                    <div class="form-group">
                      <label for="yarnCount">Yarn Count</label>
                      <input v-model="form.count" type="text" class="form-control" id="yarnCount"
                        placeholder="e.g., 20s, 30/1" />
                    </div>
                    <div class="form-group">
                      <label for="yarnType">Yarn Type</label>
                      <input v-model="form.type" type="text" class="form-control" id="yarnType"
                        placeholder="e.g., Cotton, Polyester, Blend" />
                    </div>
                  </form>
                </div>
              </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    @click="closeModal">Discard</button>
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
                <tr v-for="yarn in yarnList.data" :key="yarn.id">
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

          <vue-awesome-paginate :total-items="yarnList.total" :items-per-page="itemsPerPage" :max-pages-shown="2"
            v-model="currentPage" :on-click="onClickHandler" />

        </div>
      </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Axistance from '../../Axistance';

const yarnList = ref([]);
const form = ref({ name: '', count: '', type: '' });
const editingId = ref(null);
// Pagination state
const currentPage = ref(1)
const itemsPerPage = 10

const API_PATH = 'yarn-count';

const fetchYarns = async () => {
  try {
    const res = await Axistance.get(`${API_PATH}`, {
      params: {
        page: currentPage.value,
        limit: itemsPerPage
      }
    });
    yarnList.value = res.data;
  } catch (error) {
    console.error('Error fetching yarns:', error.response ? error.response.data : error.message);
    yarnList.value = [];
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
    fetchYarns(); // Fetch current page after add/edit
    $('#yarnModal').modal('hide');
  } catch (error) {
    console.error('Error submitting form:', error.response ? error.response.data : error.message);
    // Handle error (e.g., show validation errors to the user)
  }
};

const deleteYarn = async (id) => {
  swal({
    title: 'Are you sure?',
    text: "This data wont be revert!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Delete',
    padding: '2em'
  }).then(async function (result) {
    if (result.value) {
      await Axistance.delete(`${API_PATH}/${id}`)
        .then(response => {
          swal(
            'Deleted!',
            response.data.message,
            response.data.status
          )
          fetchYarns()
        })
    }
  })
};
watch(currentPage, () => {
  fetchYarns()
})
const onClickHandler = (page) => {
  currentPage.value = page
}

onMounted(() => {
  fetchYarns(currentPage.value);
});

// Watch for currentPage changes if you want to fetch data when it's changed programmatically elsewhere
// watch(currentPage, (newPage) => {
//   fetchYarns(newPage);
// });

</script>

<style scoped>
.modal-footer .btn+.btn {
  margin-left: 0.5rem;
}

/* Add any additional styling you need */
.table-hover tbody tr:hover {
  background-color: #f5f5f5;
}

.btn-warning,
.btn-danger {
  color: white;
  /* Ensure icon visibility if using dark icons */
}
</style>