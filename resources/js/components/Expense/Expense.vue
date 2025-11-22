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
                                                <label for="expDate">Expense Date</label>
                                                <input v-model="form.expense_date" type="date" class="form-control"
                                                    id="expDate" placeholder="Expense Date" />
                                            </div>

                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <input v-model="form.amount" type="number" step="0.01" class="form-control" id="amount"
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

            <!-- Expense Details Modal -->
            <div class="modal fade" id="expenseDetailsModal" tabindex="-1" role="dialog"
                aria-labelledby="expenseDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="expenseDetailsModalLabel">
                                Expense Details - {{ selectedDate ? formatDate(selectedDate) : '' }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div v-if="loadingDetails" class="text-center p-4">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div v-else>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Total Amount:</strong> {{ formatCurrency(expenseDetails.total_amount) }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Number of Expenses:</strong> {{ expenseDetails.count }}
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Amount</th>
                                                <th>Description</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(expense, index) in expenseDetails.expenses" :key="expense.id">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ formatCurrency(expense.amount) }}</td>
                                                <td>{{ expense.description || 'N/A' }}</td>
                                                <td>{{ formatDateTime(expense.created_at) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning mr-2" 
                                                        @click="openEditModal(expense)">Edit</button>
                                                    <button class="btn btn-sm btn-danger" 
                                                        @click="deleteExpense(expense.id)">Delete</button>
                                                </td>
                                            </tr>
                                            <tr v-if="expenseDetails.expenses && expenseDetails.expenses.length === 0">
                                                <td colspan="5" class="text-center">No expenses found for this date</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Number of Expenses</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(expense,ind) in expenseList.data" :key="expense.expense_date">
                                <td>{{ ((currentPage-1)*itemsPerPage)+ ++ind }}</td>
                                <td>{{ formatDate(expense.expense_date) }}</td>
                                <td>{{ formatCurrency(expense.total_amount) }}</td>
                                <td>{{ expense.count }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info mr-2" @click="viewExpensesByDate(expense.expense_date)">View Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <vue-awesome-paginate :total-items="expenseList.total" :items-per-page="itemsPerPage" :max-pages-shown="5"
                    v-model="currentPage" @click="onClickHandler" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Axistance from '../../Axistance'
import "vue-awesome-paginate/dist/style.css";
const expenseList = ref([])
const form = ref({ expense_date: '', amount: '', description: '' })
const editingId = ref(null)
const currentPage = ref(1)
const itemsPerPage = 10
const expenseDetails = ref({ expenses: [], total_amount: 0, count: 0 })
const selectedDate = ref(null)
const loadingDetails = ref(false)
const clearData = () => {
    form.value.expense_date = ''
    form.value.amount = ''
    form.value.description = ''
    editingId.value = ''
}
const onClickHandler = (page) => {
    currentPage.value = page
}
watch(currentPage, () => {
    fetchExpense()
})

const viewExpensesByDate = async (date) => {
    selectedDate.value = date
    loadingDetails.value = true
    expenseDetails.value = { expenses: [], total_amount: 0, count: 0 }
    
    try {
        const res = await Axistance.get('expense/by-date', {
            params: { date: date }
        })
        expenseDetails.value = res.data
        $('#expenseDetailsModal').modal('show')
    } catch (error) {
        console.error('Error fetching expense details:', error)
        swal('Error', 'Failed to load expense details', 'error')
    } finally {
        loadingDetails.value = false
    }
}

const openEditModal = (expense) => {
    form.value.expense_date = expense.expense_date
    form.value.amount = expense.amount
    form.value.description = expense.description || ''
    editingId.value = expense.id
    $('#expenseDetailsModal').modal('hide')
    $('#expenseModal').modal('show')
}

const submitCustomerForm = async () => {
    if (editingId.value) {
        await Axistance.put(`expense/${editingId.value}`, form.value)
    } else {
        await Axistance.post('expense', form.value)
    }
    clearData()
    fetchExpense()
    $('#expenseModal').modal('hide')
    // Refresh details modal if it's open
    if ($('#expenseDetailsModal').hasClass('show') && selectedDate.value) {
        viewExpensesByDate(selectedDate.value)
    }
}

const fetchExpense = async () => {
    const res = await Axistance.get('expense', {
        params: {
            page: currentPage.value,
            per_page: itemsPerPage
        }
    })
    expenseList.value = res.data
}

const formatDate = (date) => {
    if (!date) return ''
    const d = new Date(date)
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}

const formatCurrency = (amount) => {
    if (!amount) return '0.00'
    return parseFloat(amount).toFixed(2)
}

const formatDateTime = (dateTime) => {
    if (!dateTime) return ''
    const d = new Date(dateTime)
    return d.toLocaleString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const deleteExpense = async (id) => {
    swal({
      title: 'Are you sure?',
      text: "This data wont be revert!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete',
      padding: '2em'
    }).then(async function(result) {
      if (result.value) {
        await Axistance.delete(`expense/${id}`)
          .then(response => {
            swal(
              'Deleted!',
              response.data.message || 'Expense deleted successfully',
              response.data.status || 'success'
            )
            fetchExpense()
            // Refresh details modal if it's open
            if ($('#expenseDetailsModal').hasClass('show') && selectedDate.value) {
                viewExpensesByDate(selectedDate.value)
            }
        })
      }
    })
}
onMounted(() => {
    fetchExpense()
})
</script>