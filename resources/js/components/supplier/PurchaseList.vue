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
                                <td>{{ new Date(purchase.purchase_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) }}</td>
                                <td>{{ (purchase.supplier?.name || 'N/A') }}</td>
                                <td>{{ formatCurrency(purchase.total_amount) }}</td>
                                <td class="status-cell">
                                    <span :class="getStatusClass(purchase.status)">
                                        {{ getStatusLabel(purchase.status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning mr-2" @click="openEditModal(purchase)">Edit</button>
                                    <button class="btn btn-sm btn-danger" @click="deletePurchase(purchase.id)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <EditPurchase v-if="selectedPurchase" :purchase="selectedPurchase" @purchase-updated="handlePurchaseUpdated" @close-modal="closeEditModal"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Axistance from '../../Axistance'
import EditPurchase from './EditPurchase.vue';

const purchaseList = ref([])
const selectedPurchase = ref(null);
const url = ref(baseUrl)

const openEditModal = (purchase) => {
    selectedPurchase.value = purchase;
}

const closeEditModal = () => {
    selectedPurchase.value = null;
}

const fetchPurchase = async () => {
    try {
        const res = await Axistance.get(baseUrl + 'purchase')
        purchaseList.value = res.data
    } catch (error) {
        console.error('Error fetching purchases:', error);
        // Here you could add user-facing error handling, e.g., a toast notification.
    }
}

const deletePurchase = async (id) => {
    if (confirm('Are you sure?')) {
        try {
            await Axistance.delete(baseUrl + `purchase/${id}`);
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