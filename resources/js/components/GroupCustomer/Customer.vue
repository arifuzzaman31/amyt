<template>
    <div id="tableCaption" class="col-lg-12 col-12 layout-spacing mt-2">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Customer</h4>
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                            data-target="#customerModalCenter">
                            Add Customer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bootstrap Modal -->
            <div class="modal fade" id="customerModalCenter" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ editingId ? 'Edit Customer' : 'New Customer' }}</h5>
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
                                                <label for="customerGroup">Customer Group</label>
                                                <select v-model="form.customer_group_id" class="form-control"
                                                    id="customerGroup">
                                                    <option disabled value="0">Select a group</option>
                                                    <option v-for="group in customerGroups" :key="group.id"
                                                        :value="group.id">
                                                        {{ group.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="customerName">Customer Name</label>
                                                <input v-model="form.name" type="text" class="form-control"
                                                    id="customerName" placeholder="Enter Name" />
                                            </div>

                                            <div class="form-group">
                                                <label for="company_name">Company Name</label>
                                                <input v-model="form.company_name" type="text" class="form-control"
                                                    id="company_name" placeholder="Enter Company name" />
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input v-model="form.address" type="text" class="form-control"
                                                    id="address" placeholder="Enter address" />
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input v-model="form.email" type="text" class="form-control" id="email"
                                                    placeholder="Enter Email" />
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input v-model="form.phone" type="text" class="form-control" id="phone"
                                                    placeholder="Enter Phone" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                @click="clearData">Discard</button>
                            <button type="button" class="btn btn-primary" @click="submitCustomerForm">
                                {{ editingId ? 'Update' : 'Add' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input 
                                v-model="searchTerm" 
                                type="text" 
                                class="form-control" 
                                placeholder="Search by name, company, email, or phone..."
                                @input="handleSearch"
                            />
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-4">
                        <caption>List of all Customer</caption>
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th>Name</th>
                                <th>company name</th>
                                <th>Group</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th class="">Status</th>
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(customer,ind) in customerList.data" :key="customer.id">
                                <td>{{ ((currentPage-1)*itemsPerPage)+ ++ind }}</td>
                                <td>{{ customer.name }}</td>
                                <td>{{ customer.company_name }}</td>
                                <td>{{ customer.customer_group?.name }}</td>
                                <td>{{ customer.email }}</td>
                                <td>{{ customer.phone }}</td>
                                <td class=""><span class=" shadow-none badge outline-badge-primary">{{ customer.status
                                    == 1 ? 'active' : 'inactive' }}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning mr-2"
                                        @click="openEditModal(customer)">Edit</button>
                                    <button class="btn btn-sm btn-danger"
                                        @click="deleteCustomer(customer.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <vue-awesome-paginate :total-items="customerList.total" :items-per-page="itemsPerPage" :max-pages-shown="5"
                    v-model="currentPage" @click="onClickHandler" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Axistance from '../../Axistance'
import "vue-awesome-paginate/dist/style.css";
const customerGroups = ref([])
const customerList = ref([])
const form = ref({ customer_group_id: 0, name: '', company_name: '', address: '', email: '', phone: '' })
const editingId = ref(null)
const currentPage = ref(1)
const itemsPerPage = 10
const searchTerm = ref('')
let searchTimeout = null
const clearData = () => {
    form.value.customer_group_id = 0
    form.value.name = ''
    form.value.company_name = ''
    form.value.address = ''
    form.value.email = ''
    form.value.phone = ''
    editingId.value = ''
}

const fetchCustomers = async () => {
    const params = {
        page: currentPage.value,
        limit: itemsPerPage
    }
    if (searchTerm.value.trim()) {
        params.search = searchTerm.value.trim()
    }
    const res = await Axistance.get('customer', { params })
    customerList.value = res.data
}
const openEditModal = (customer) => {
    form.value.customer_group_id = customer.customer_group_id
    form.value.name = customer.name
    form.value.company_name = customer.company_name
    form.value.address = customer.address
    form.value.email = customer.email
    form.value.phone = customer.phone
    editingId.value = customer.id
    $('#customerModalCenter').modal('show')
}
const submitCustomerForm = async () => {
    if (editingId.value) {
        await Axistance.put(`customer/${editingId.value}`, form.value)
    } else {
        await Axistance.post('customer', form.value)
    }
    clearData()
    fetchCustomers()
    $('#customerModalCenter').modal('hide')
}

const editCustomer = (group) => {
    form.value.name = group.name
    form.value.company_name = group.company_name
    form.value.customer_group_id = customer.customer_group_id
    form.value.address = customer.address
    form.value.email = customer.email
    form.value.phone = customer.phone
    editingId.value = group.id
    $('#customerModalCenter').modal('show')
}

const fetchGroups = async () => {
    const res = await Axistance.get('customer-groups')
    customerGroups.value = res.data
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
        await Axistance.delete(`customer/${id}`)
          .then(response => {
            swal(
              'Deleted!',
              response.data.message,
              response.data.status
            )
            fetchCustomers()
        })
      }
    })
}

const handleSearch = () => {
    // Clear existing timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }
    // Reset to first page when searching
    currentPage.value = 1
    // Debounce search - wait 500ms after user stops typing
    searchTimeout = setTimeout(() => {
        fetchCustomers()
    }, 500)
}

watch(currentPage, () => {
    fetchCustomers()
})
const onClickHandler = (page) => {
    currentPage.value = page
}
onMounted(() => {
    fetchGroups()
    fetchCustomers()
})
</script>