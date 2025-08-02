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
                                <th>IsStocked</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(purchase,ind) in purchaseList.data" :key="purchase.id">
                                <td>{{ ((currentPage-1)*itemsPerPage)+ ++ind }}</td>
                                <td>{{ purchase.challan_no }}</td>
                                <td>{{ new Date(purchase.purchase_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) }}</td>
                                <td>{{ (purchase.supplier?.name || 'N/A') }}</td>
                                <td>{{ formatCurrency(purchase.total_amount) }}</td>
                                <td>
                                    <span :class="getStatusClass(purchase.is_stocked)">
                                        {{ purchase.is_stocked == 0 ? 'Not Yet' : 'Stocked' }}
                                    </span>
                                </td>
                                <td>
                                    <span :class="getStatusClass(purchase.status)">
                                        {{ getStatusLabel(purchase.status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown custom-dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <a type="button" :class="'dropdown-item ' + (getStatusLabel(purchase.status) == 'Approved' ? 'disabled text-muted' : '')" @click="updateStatus(purchase.id)" href="javascript:void(0);">Approved</a>
                                            <a type="button" :class="'dropdown-item ' + (purchase.is_stocked == 1 ? 'disabled text-muted' : '')" @click="loadToStock(purchase.id)" href="javascript:void(0);">Load to Stock</a>
                                            <a type="button" class="dropdown-item" @click="openEditModal(purchase)" href="javascript:void(0);">Edit</a>
                                            <a type="button" class="dropdown-item" @click="deletePurchase(purchase.id)" href="javascript:void(0);">Delete</a>
                                        </div>
                                    </div>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <vue-awesome-paginate :total-items="purchaseList.total" :items-per-page="itemsPerPage" :max-pages-shown="5"
                    v-model="currentPage" @click="onClickHandler" />
                </div>
                <EditPurchase v-if="selectedPurchase" :purchase="selectedPurchase" @purchase-updated="handlePurchaseUpdated" @close-modal="closeEditModal"/>
                <!-- // Add a modal component for editing purchase take payment info then approve -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Axistance from '../../Axistance'
import EditPurchase from './EditPurchase.vue';
import "vue-awesome-paginate/dist/style.css";
const purchaseList = ref([])
const selectedPurchase = ref(null);
const url = ref(baseUrl)
const currentPage = ref(1)
const itemsPerPage = 2
const openEditModal = (purchase) => {
    selectedPurchase.value = purchase;
}
watch(currentPage, () => {
    fetchPurchase()
})
const onClickHandler = (page) => {
    currentPage.value = page
}
const closeEditModal = () => {
    selectedPurchase.value = null;
}
const updateStatus = (id) => {
    swal({
      title: 'Are you sure?',
      text: "Approve this purchase?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '2em'
    }).then(async function(result) {
        if (result.value) {
            await Axistance.post(`purchase/${id}/approve`)
          .then(response => {
            swal(
              'Loaded!',
              response.data.message,
              response.data.status
            )
            fetchPurchase();
        })
      }
    })
}
const loadToStock = (id) => {
    swal({
      title: 'Are you sure?',
      text: "Load this purchase to stock?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '2em'
    }).then(async function(result) {
      if (result.value) {
        await Axistance.post(`purchase-to-stock/${id}`)
          .then(response => {
            swal(
              'Loaded!',
              response.data.message,
              response.data.status
            )
            fetchPurchase();
        })
      }
    })
}

const fetchPurchase = async () => {
    try {
        const res = await Axistance.get('purchase', {
        params: {
            page: currentPage.value,
            per_page: itemsPerPage
        }
    })
        purchaseList.value = res.data
    } catch (error) {
        console.error('Error fetching purchases:', error);
        // Here you could add user-facing error handling, e.g., a toast notification.
    }
}

const deletePurchase = async (id) => {
    if (confirm('Are you sure?')) {
        try {
            await Axistance.delete(`purchase/${id}`);
            fetchPurchase();
        } catch (error) {
            console.error('Error deleting purchase:', error);
            // Here you could add user-facing error handling.
        }
    }
}

const handlePurchaseUpdated = () => {
    fetchPurchase();
    closeEditModal();
}

/**
 * Formats a number as a currency string (e.g., USD).
 * @param {number} amount - The amount to format.
 * @returns {string} - The formatted currency string.
 */
const formatCurrency = (amount) => {
    if (typeof amount !== 'number') {
        return '';
    }
    return new Intl.NumberFormat('en-US').format(amount);
}

/**
 * Gets the display label for a given status code.
 * @param {number} status - The status code.
 * @returns {string} - The status label.
 */
const getStatusLabel = (status) => {
    const statusMap = {
        0: 'Pending',
        1: 'Approved',
        2: 'Rejected',
        3: 'Draft',
        4: 'Closed'
    };
    return statusMap[status] || 'Unknown';
}

/**
 * Gets the CSS class for a given status code for styling.
 * @param {number} status - The status code.
 * @returns {string} - The corresponding CSS class.
 */
const getStatusClass = (status) => {
    const classMap = {
        0: 'badge badge-light-warning',
        1: 'badge badge-light-success',
        2: 'badge badge-light-danger',
        3: 'badge badge-light-info',
        4: 'badge badge-light-primary'
    };
    return classMap[status] || 'badge';
}

onMounted(() => {
    fetchPurchase()
})
</script>

<style scoped>
.status-cell {
    width: 20%;
    white-space: nowrap;
}

.badge {
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 10px;
    text-transform: capitalize;
}

.badge-light-warning {
    background-color: rgba(226, 160, 63, 0.1);
    color: #e2a03f;
}

.badge-light-success {
    background-color: rgba(26, 188, 156, 0.1);
    color: #1abc9c;
}

.badge-light-danger {
    background-color: rgba(231, 81, 90, 0.1);
    color: #e7515a;
}

.badge-light-info {
    background-color: rgba(33, 150, 243, 0.1);
    color: #2196f3;
}

.badge-light-primary {
    background-color: rgba(67, 97, 238, 0.1);
    color: #4361ee;
}
</style>