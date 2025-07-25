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
                    <tr v-for="expense in expenseCategories" :key="expense.id">
                        <td>{{ expense.id }}</td>
                        <td>{{ expense.name }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(expense)">Edit</button>
                            <button class="btn btn-sm btn-danger" @click="deleteCategory(expense.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const expenseCategories = ref([])
const form = ref({ name: '' })
const editingId = ref(null)

const fetchExpenseCategory = async () => {
    const res = await axios.get('expense/category')
    expenseCategories.value = res.data
}
const openEditModal = (group) => {
    form.value.name = group.name
    editingId.value = group.id
    $('#expenseCategoryModal').modal('show')
}
const submitForm = async () => {
    if (editingId.value) {
        await axios.put(`expense/category/${editingId.value}`, form.value)
    } else {
        await axios.post('expense/category', form.value)
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
    if (confirm('Are you sure?')) {
        await axios.delete(`expense/category/${id}`)
        fetchExpenseCategory()
    }
}

onMounted(fetchExpenseCategory)
</script>

<style scoped>
.modal-footer .btn+.btn {
    margin-left: 0.5rem;
}
</style>