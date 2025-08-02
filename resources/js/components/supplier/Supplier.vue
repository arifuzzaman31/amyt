<template>
    <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Supplier</h4>
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                            data-target="#supplierModal">
                            Add Supplier
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bootstrap Modal -->
            <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ editingId ? 'Edit Supplier' : 'New Supplier' }}</h5>
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
                                                <label for="SupplierName">Supplier Name</label>
                                                <input v-model="form.name" type="text" class="form-control"
                                                    id="SupplierName" placeholder="Enter Supplier Name" />
                                            </div>

                                            <div class="form-group">
                                                <label for="company_name">Company Name</label>
                                                <input v-model="form.company_name" type="text" class="form-control"
                                                    id="company_name" placeholder="Enter Company name" />
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input v-model="form.address" type="text" class="form-control" id="address"
                                                    placeholder="Enter address" />
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="clearData">Discard</button>
                            <button type="button" class="btn btn-primary" @click="submitSupplierForm">
                                {{ editingId ? 'Update' : 'Add' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table mb-4">
                        <caption>List of Supplier</caption>
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th>Name</th>
                                <th>company name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th class="">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(supplier,ind) in supplierList.data" :key="supplier.id">
                                <td>{{ ((currentPage-1)*itemsPerPage)+ ++ind }}</td>
                                <td>{{ supplier.name }}</td> 
                                <td>{{ supplier.company_name }}</td> 
                                <td>{{ supplier.email }}</td>
                                <td>{{ supplier.phone }}</td>
                                <td class=""><span class=" shadow-none badge outline-badge-primary">{{ supplier.status == 1 ? 'active' : 'inactive'}}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(supplier)">Edit</button>
                                    <button class="btn btn-sm btn-danger" @click="deleteSupplier(supplier.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <vue-awesome-paginate :total-items="supplierList.total" :items-per-page="itemsPerPage" :max-pages-shown="5"
                    v-model="currentPage" @click="onClickHandler" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted,watch } from 'vue'
import Axistance from '../../Axistance'
import "vue-awesome-paginate/dist/style.css";
const supplierList = ref([])
const form = ref({ name: '',company_name:'', address: '', email: '', phone: '', status: 1 })
const editingId = ref(null)
const currentPage = ref(1)
const itemsPerPage = 10
const clearData = () => {
    form.value.name = ''
    form.value.company_name = ''
    form.value.address = ''
    form.value.email = ''
    form.value.phone = ''
    form.value.status = 1
    editingId.value = ''
}

const openEditModal = (supplier) => {
    form.value.name = supplier.name
    form.value.company_name = supplier.company_name
    form.value.address = supplier.address
    form.value.email = supplier.email
    form.value.phone = supplier.phone
    form.value.status = supplier.status
    editingId.value = supplier.id
    $('#supplierModal').modal('show')
}
const submitSupplierForm = async () => {
    if (editingId.value) {
        await Axistance.put(`supplier/${editingId.value}`, form.value)
    } else {
        await Axistance.post('supplier', form.value)
    }
    clearData()
    fetchSupplier()
    $('#supplierModal').modal('hide')
}

const editSupplier = (supplier) => {
    form.value.name = supplier.name
    form.value.company_name = supplier.company_name
    form.value.address = supplier.address
    form.value.email = supplier.email
    form.value.phone = supplier.phone
    form.value.status = supplier.status
    editingId.value = supplier.id
    $('#supplierModal').modal('show')
}
watch(currentPage, () => {
    fetchSupplier()
})
const onClickHandler = (page) => {
    currentPage.value = page
}
const fetchSupplier = async () => {
    const res = await Axistance.get('supplier', {
        params: {
            page: currentPage.value,
            per_page: itemsPerPage
        }
    })
    supplierList.value = res.data
}

const deleteSupplier = async (id) => {
    if (confirm('Are you sure?')) {
        await Axistance.delete(`supplier/${id}`)
    }
}
onMounted(() => {
    fetchSupplier()
})
</script>