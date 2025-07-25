<template>
    <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Total Stock</h4>
                    </div>
                </div>
            </div>

            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table mb-4">
                        <caption>List of all Item Stock</caption>
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th>Yarn</th>
                                <th>Type</th>
                                <th>Customer Qty</th>
                                <th>Amyt Qty</th>
                                <th>Total Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(stock,index) in amytCustomerStockList" :key="index">
                                <td>{{ ++index }}</td>
                                <td>{{ stock.name }}</td>
                                <td>{{ stock.type }}</td>
                                <td>{{ stock.customer_stock_quantity }}</td>
                                <td>{{ stock.amyt_stock_quantity }}</td>
                                <td>{{ Number(stock.customer_stock_quantity)+Number(stock.amyt_stock_quantity) }}</td>
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
import Axistance from '../../Axistance'

const amytCustomerStockList = ref([])
const url = ref(baseUrl)

const fetchAmytCustomerStock = async () => {
    try {
        const res = await Axistance.get('amyt-customer-stock-list?vendor=customer');
        amytCustomerStockList.value = res.data.data
        console.log('Fetched Amyt Customer Stock:', res.data.data);
    } catch (error) {
        console.error('Error fetching purchases:', error);
        // Here you could add user-facing error handling, e.g., a toast notification.
    }
}

const deleteCustomerStock = async (id) => {
    if (confirm('Are you sure?')) {
        try {
            await Axistance.delete(`purchase/${id}`);
            fetchAmytCustomerStock();
        } catch (error) {
            console.error('Error deleting purchase:', error);
            // Here you could add user-facing error handling.
        }
    }
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
    fetchAmytCustomerStock()
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