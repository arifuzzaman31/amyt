<template>
    <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Purchase</h4>
                        <a :href="url+'create-purchase'" class="btn btn-primary mb-3">
                            Add Purchase
                        </a>
                    </div>
                </div>
            </div>

            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table mb-4">
                        <caption>List of all Purchase</caption>
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th>Challan No</th>
                                <th>Purchase Date</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="purchase in purchaseList" :key="purchase.id">
                                <td>{{ purchase.id }}</td>
                                <td>{{ purchase.challan_no }}</td>
                                <td>{{ purchase.purchase_date }}</td>
                                <td>{{ purchase.supplier_id }}</td>
                                <td>{{ purchase.total_amount }}</td>
                                <td class="text-wrap" style="width:35%;">{{ purchase.status }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(purchase)">Edit</button>
                                    <button class="btn btn-sm btn-danger" @click="deletePurchase(purchase.id)">Delete</button>
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

const purchaseList = ref([])
const form = ref({ supplier_id: 0, purchase_date: '', challan_no: '', document_link: '',
 total_amount: '', payment_status: '', status: '' })
const editingId = ref(null)
const url = ref(baseUrl)

const clearData = () => {
    form.value.supplier_id = 0
    form.value.purchase_date = ''
    form.value.challan_no = ''
    form.value.document_link = ''
    form.value.total_amount = ''
    form.value.payment_status = ''
    form.value.status = ''
    editingId.value = ''
}

const openEditModal = (purchase) => {
    form.value.supplier_id = purchase.supplier_id
    form.value.purchase_date = purchase.purchase_date
    form.value.challan_no = purchase.challan_no
    form.value.document_link = purchase.document_link
    form.value.total_amount = purchase.total_amount
    form.value.payment_status = purchase.payment_status
    form.value.status = purchase.status
    editingId.value = customer.id
    $('#purchaseModal').modal('show')
}
const submitCustomerForm = async () => {
    if (editingId.value) {
        await axios.put(baseUrl + `purchase/${editingId.value}`, form.value)
    } else {
        await axios.post(baseUrl + 'purchase', form.value)
    }
    clearData()
    fetchPurchase()
    $('#purchaseModal').modal('hide')
}

const editPurchase = (purchase) => {
    form.value.supplier_id = purchase.supplier_id
    form.value.purchase_date = purchase.purchase_date
    form.value.challan_no = purchase.challan_no
    form.value.document_link = purchase.document_link
    form.value.total_amount = purchase.total_amount
    form.value.payment_status = purchase.payment_status
    form.value.status = purchase.status
    editingId.value = purchase.id
    $('#purchaseModal').modal('show')
}

const fetchPurchase = async () => {
    const res = await axios.get(baseUrl + 'purchase')
    purchaseList.value = res.data
}

const deleteCustomer = async (id) => {
    if (confirm('Are you sure?')) {
        await axios.delete(baseUrl + `purchase/${id}`)
    }
}
onMounted(() => {
    fetchPurchase()
})
</script>