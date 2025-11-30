<template>
    <div id="tableCaption" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Customer Stock</h4>
                        <a :href="url+'customer-stock-page'" class="btn btn-primary mb-3">
                            Add Customer Stock
                        </a>
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
                                placeholder="Search by challan no or customer name..."
                                @input="handleSearch"
                            />
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-4">
                        <caption>List of all Customer Stock</caption>
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th>Challan No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>IsStocked</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(stock,ind) in customerStockList.data" :key="stock.id">
                                <td>{{ ((currentPage-1)*itemsPerPage)+ ++ind }}</td>
                                <td>{{ stock.challan_no }}</td>
                                <td>{{ new Date(stock.in_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) }}</td>
                                <td>{{ (stock.customer?.name || 'N/A') }}</td>
                                <td>{{ formatCurrency(stock.total_amount) }}</td>
                                <td>
                                    <span :class="getStatusClass(stock.is_stocked)">
                                        {{ stock.is_stocked == 0 ? 'Not Yet' : 'Stocked' }}
                                    </span>
                                </td>
                                <td>
                                    <span :class="getStatusClass(stock.status)">
                                        {{ getStatusLabel(stock.status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown custom-dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <a type="button" :class="'dropdown-item ' + (getStatusLabel(stock.status) == 'Approved' ? 'disabled text-muted' : '')" @click="updateStatus(stock.id)" href="javascript:void(0);">Approved</a>
                                            <a type="button" :class="'dropdown-item ' + (stock.is_stocked == 1 ? 'disabled text-muted' : '')" @click="loadToStock(stock.id)" href="javascript:void(0);">Load to Stock</a>
                                            <!-- <a type="button" class="dropdown-item" @click="openEditModal(stock)" href="javascript:void(0);">Edit</a> -->
                                            <a type="button" class="dropdown-item" @click="deleteCustomerStock(stock.id)" href="javascript:void(0);">Delete</a>
                                        </div>
                                    </div>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <vue-awesome-paginate :total-items="customerStockList.total" :items-per-page="itemsPerPage" :max-pages-shown="5"
                    v-model="currentPage" @click="onClickHandler" />
               </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted,watch } from 'vue'
import Axistance from '../../Axistance'
import "vue-awesome-paginate/dist/style.css";
const customerStockList = ref([])
const selectedStock = ref(null);
const url = ref(baseUrl)
const currentPage = ref(1)
const itemsPerPage = 10
const searchTerm = ref('')
let searchTimeout = null
const openEditModal = (stock) => {
    // console.log(purchase)
    selectedStock.value = stock;
}

const fetchCustomerStock = async () => {
    try {
        const params = {
            page: currentPage.value,
            per_page: itemsPerPage
        }
        if (searchTerm.value.trim()) {
            params.search = searchTerm.value.trim()
        }
        const res = await Axistance.get('stock-list?vendor=customer', { params });
        customerStockList.value = res.data
    } catch (error) {
        console.error('Error fetching customer stock:', error);
        // Here you could add user-facing error handling, e.g., a toast notification.
    }
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
        fetchCustomerStock()
    }, 500)
}
watch(currentPage, () => {
    fetchCustomerStock()
})
const onClickHandler = (page) => {
    currentPage.value = page
}
const deleteCustomerStock = async (id) => {
    if (confirm('Are you sure?')) {
        try {
            await Axistance.delete(`purchase/${id}`);
            fetchCustomerStock();
        } catch (error) {
            console.error('Error deleting purchase:', error);
            // Here you could add user-facing error handling.
        }
    }
}

const loadToStock = (id) => {
    if (confirm('Are you sure you want to load this customer to stock?')) {
        Axistance.post(`customer-item-to-stock/${id}`)
            .then((response,error) => {
                alert(response.data.message || 'Customer item loaded to stock successfully.');
                fetchCustomerStock();
            })
            .catch(error => {
                console.error('Error loading Customer item to stock:', error);
                // Here you could add user-facing error handling, e.g., a toast notification.
            });
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
    fetchCustomerStock()
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