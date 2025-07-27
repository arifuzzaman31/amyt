<template>
    <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Expense Category</h4>
                        <button type="button" class="btn btn-primary mb-3" data-target="#expenseCategoryModal"
                            data-toggle="modal">
                            Add Expense Category
                        </button>
                    </div>
                </div>
            </div>
            <!-- Bootstrap Modal -->
            <div class="modal fade" id="expenseCategoryModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ editingId ? 'Edit Category' : 'Add Category' }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="statbox widget box-shadow">
                                <div class="widget-header">
                                    <form @submit.prevent="submitForm">
                                        <div class="form-group">
                                            <label for="CategoryName">Category Name</label>
                                            <input v-model="form.name" type="text" class="form-control"
                                                id="CategoryName" placeholder="Enter Category name" />
                                        </div>
                                    </form>
                                </div>
                            </div>
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
                    <tr v-for="(expense,ind) in expenseCategories.data" :key="ind">
                        <td>{{ ++ind }}</td>
                        <td>{{ expense.name }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(expense)">Edit</button>
                            <button class="btn btn-sm btn-danger" @click="deleteCategory(expense.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <vue-awesome-paginate :total-items="expenseCategories.total" :items-per-page="itemsPerPage" :max-pages-shown="5"
                    v-model="currentPage" @click="onClickHandler" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted,watch } from 'vue'
import Axistance from '../../Axistance'
const expenseCategories = ref([])
const form = ref({ name: '' })
const editingId = ref(null)
const currentPage = ref(1)
const itemsPerPage = 10
const fetchExpenseCategory = async () => {
    const res = await Axistance.get('expense/category', {
        params: {
            page: currentPage.value,
            limit: itemsPerPage
        }
    })
    expenseCategories.value = res.data
}
const openEditModal = (group) => {
    form.value.name = group.name
    editingId.value = group.id
    $('#expenseCategoryModal').modal('show')
}
const submitForm = async () => {
    if (editingId.value) {
        await Axistance.put(`expense/category/${editingId.value}`, form.value)
    } else {
        await Axistance.post('expense/category', form.value)
    }

    form.value.name = ''
    editingId.value = null
    fetchExpenseCategory()
    $('#expenseCategoryModal').modal('hide')
}

const editCategory = (group) => {
    form.value.name = group.name
    editingId.value = group.id
    $('#expenseCategoryModal').modal('show')
}

const deleteCategory = async (id) => {
    swal({
      title: 'Are you sure?',
      text: "This data wont be revert!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      padding: '2em'
    }).then(async function(result) {
      if (result.value) {
        await Axistance.delete(`expense/category/${id}`)
          .then(response => {
            swal(
              'Deleted!',
              response.data.message,
              response.data.status
            )
            fetchExpenseCategory()
        })
      }
    })
}
watch(currentPage, () => {
    fetchCustomers()
})
const onClickHandler = (page) => {
    currentPage.value = page
}
onMounted(fetchExpenseCategory)
</script>

<style scoped>
.modal-footer .btn+.btn {
    margin-left: 0.5rem;
}
</style>