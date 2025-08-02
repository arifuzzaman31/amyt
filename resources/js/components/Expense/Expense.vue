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
                            <tr v-for="(expense,ind) in expenseList.data" :key="expense.id">
                                <td>{{ ((currentPage-1)*itemsPerPage)+ ++ind }}</td>
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
const expenseCategories = ref([])
const expenseList = ref([])
const form = ref({ expense_category_id: 0, expense_date: '', amount: '', description: '' })
const editingId = ref(null)
const currentPage = ref(1)
const itemsPerPage = 10
const clearData = () => {
    form.value.expense_category_id = 0
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
const fetchExpenseCategory = async () => {
    const res = await Axistance.get('expense/category')
    customerList.value = res.data
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
        await Axistance.put(`expense/${editingId.value}`, form.value)
    } else {
        await Axistance.post('expense', form.value)
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
    const res = await Axistance.get('expense', {
        params: {
            page: currentPage.value,
            per_page: itemsPerPage
        }
    })
    expenseList.value = res.data
}

const deleteCustomer = async (id) => {
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
              response.data.message,
              response.data.status
            )
            fetchExpense()
        })
      }
    })
}
onMounted(() => {
    fetchExpense()
    fetchExpenseCategory()
})
</script>