<template>
    <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Expense</h4>
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                            data-target="#expenseModal">
                            Add Expense
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bootstrap Modal -->
            <div class="modal fade" id="expenseModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ editingId ? 'Edit Expense' : 'New Expense' }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="statbox widget box-shadow">
                                <div class="">
                                    <div class="widget-header">
                                        <form @submit.prevent="submitForm">
                                            <div class="form-group">
                                                <label for="expCategory">Expense Category</label>
                                                <select v-model="form.expense_category_id" class="form-control"
                                                    id="expCategory">
                                                    <option disabled value="0">Select Expense Category</option>
                                                    <option v-for="category in expenseCategories" :key="category.id"
                                                        :value="category.id">
                                                        {{ category.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="expDate">Expense Date</label>
                                                <input v-model="form.expense_date" type="date" class="form-control"
                                                    id="expDate" placeholder="Expense Date" />
                                            </div>

                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <input v-model="form.amount" type="number" class="form-control" id="amount"
                                                    placeholder="Enter Amount" />
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea v-model="form.description" rows="3" class="form-control" id="description"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="clearData">Discard</button>
                            <button type="button" class="btn btn-primary" @click="submitCustomerForm">
                                {{ editingId ? 'Update' : 'Add' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table mb-4">
                        <caption>List of all Expense</caption>
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th>Expense Category</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="expense in expenseList" :key="expense.id">
                                <td>{{ expense.id }}</td>
                                <td>{{ expense.expense_category.name }}</td>
                                <td>{{ expense.expense_date }}</td>
                                <td>{{ expense.amount }}</td>
                                <td class="text-wrap" style="width:35%;">{{ expense.description }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(expense)">Edit</button>
                                    <button class="btn btn-sm btn-danger" @click="deleteExpense(expense.id)">Delete</button>
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
import axios from 'axios'

const expenseCategories = ref([])
const expenseList = ref([])
const form = ref({ expense_category_id: 0, expense_date: '', amount: '', description: '' })
const editingId = ref(null)

const clearData = () => {
    form.value.expense_category_id = 0
    form.value.expense_date = ''
    form.value.amount = ''
    form.value.description = ''
    editingId.value = ''
}

const fetchExpenseCategory = async () => {
    const res = await axios.get(baseUrl + 'expense/category')
    expenseCategories.value = res.data
}
const openEditModal = (customer) => {
    form.value.expense_category_id = customer.expense_category_id
    form.value.expense_date = customer.expense_date
    form.value.amount = customer.amount
    form.value.description = customer.description
    editingId.value = customer.id
    $('#expenseModal').modal('show')
}
const submitCustomerForm = async () => {
    if (editingId.value) {
        await axios.put(baseUrl + `expense/${editingId.value}`, form.value)
    } else {
        await axios.post(baseUrl + 'expense', form.value)
    }
    clearData()
    fetchExpense()
    $('#expenseModal').modal('hide')
}

const editExpense = (group) => {
    form.value.expense_date = group.expense_date
    form.value.expense_category_id = customer.expense_category_id
    form.value.amount = customer.amount
    form.value.description = customer.description
    editingId.value = group.id
    $('#expenseModal').modal('show')
}

const fetchExpense = async () => {
    const res = await axios.get(baseUrl + 'expense')
    expenseList.value = res.data
}

const deleteCustomer = async (id) => {
    if (confirm('Are you sure?')) {
        await axios.delete(baseUrl + `expense/${id}`)
        fetchExpenseCategory()
    }
}
onMounted(() => {
    fetchExpense()
    fetchExpenseCategory()
})
</script>