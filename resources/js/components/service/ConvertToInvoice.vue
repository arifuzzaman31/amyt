<template>
    <div class="modal animated rotateInDownLeft custo-rotateInDownLeft show" id="convertToInvoiceModal" tabindex="-1"
        role="dialog" aria-labelledby="convertToInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-default text-white mx-3">
                    <h5 class="modal-title" id="convertToInvoiceModalLabel">Convert Challan to Invoice</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <form @submit.prevent="submitConvertForm()">
                        <!-- Service Details Section -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Service Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label font-weight-bold">Invoice No</label>
                                        <input type="text" class="form-control" v-model="serviceInfo.invoice_no" readonly />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label font-weight-bold">Service Date</label>
                                        <input type="text" class="form-control" :value="formatDate(serviceInfo.service_date)" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Edit Items (Quantity & Price)</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Yarn Count</th>
                                                <th>Color</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Extra Quantity</th>
                                                <th>Extra Qty Price</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in serviceInfo.items" :key="index">
                                                <td>{{ item.yarn_count?.name || 'N/A' }}</td>
                                                <td>{{ item.color?.name || 'N/A' }}</td>
                                                <td>
                                                    <input v-model.number="item.quantity"
                                                        class="form-control form-control-sm"
                                                        type="number" step="0.01" min="0"
                                                        placeholder="Quantity" required />
                                                </td>
                                                <td>
                                                    <input v-model.number="item.unit_price"
                                                        class="form-control form-control-sm"
                                                        type="number" step="0.01" min="0"
                                                        placeholder="Unit Price" required />
                                                </td>
                                                <td>
                                                    <input v-model.number="item.extra_quantity"
                                                        class="form-control form-control-sm"
                                                        type="number" step="0.01" min="0"
                                                        placeholder="Extra Quantity" />
                                                </td>
                                                <td>
                                                    <input v-model.number="item.extra_quantity_price"
                                                        class="form-control form-control-sm"
                                                        type="number" step="0.01" min="0"
                                                        placeholder="Extra Qty Price" />
                                                </td>
                                                <td class="font-weight-bold text-right">
                                                    {{ calculateTotalPrice(item) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5" :disabled="isSubmitting">
                                    <span v-if="isSubmitting">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Converting...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-file-invoice mr-2"></i>Convert to Invoice
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue';
import Axistance from '../../Axistance';

const props = defineProps({
    service: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['invoice-converted', 'close-modal']);
const isSubmitting = ref(false);
const serviceInfo = ref({
    id: null,
    invoice_no: '',
    service_date: '',
    customer: null,
    items: []
});

const parseNumber = (value) => {
    if (!value) return 0;
    // Remove commas and parse as float
    const cleaned = String(value).replace(/,/g, '');
    return parseFloat(cleaned) || 0;
};

watch(() => props.service, (newVal) => {
    if (newVal) {
        serviceInfo.value = {
            id: newVal.id,
            invoice_no: newVal.invoice_no || '',
            service_date: newVal.service_date || '',
            customer: newVal.customer || null,
            items: (newVal.items || []).map(item => ({
                id: item.id,
                yarn_count_id: item.yarn_count_id,
                yarn_count: item.yarn_count,
                color_id: item.color_id,
                color: item.color,
                quantity: parseNumber(item.quantity),
                unit_price: parseNumber(item.unit_price),
                extra_quantity: parseNumber(item.extra_quantity),
                extra_quantity_price: parseNumber(item.extra_quantity_price),
                unit_attr_id: item.unit_attr_id,
                weight_attr_id: item.weight_attr_id,
                gross_weight: item.gross_weight,
                net_weight: item.net_weight,
                bobin: item.bobin,
                remark: item.remark
            }))
        };
        nextTick(() => {
            $('#convertToInvoiceModal').modal('show');
        });
    }
}, { immediate: true, deep: true });

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const calculateTotalPrice = (item) => {
    const quantity = parseFloat(item.quantity) || 0;
    const unitPrice = parseFloat(item.unit_price) || 0;
    const extraQuantity = parseFloat(item.extra_quantity) || 0;
    const extraQuantityPrice = parseFloat(item.extra_quantity_price) || 0;
    
    const regularPrice = quantity * unitPrice;
    const extraPrice = extraQuantity * extraQuantityPrice;
    const total = regularPrice + extraPrice;
    
    return total.toFixed(2);
};

const submitConvertForm = async () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    try {
        const dataToSend = {
            dataItem: serviceInfo.value.items.map(item => ({
                id: item.id,
                yarn_count_id: item.yarn_count_id,
                quantity: item.quantity,
                unit_price: item.unit_price,
                extra_quantity: item.extra_quantity || 0,
                extra_quantity_price: item.extra_quantity_price || 0,
                unit_attr_id: item.unit_attr_id,
                color_id: item.color_id,
                weight_attr_id: item.weight_attr_id,
                gross_weight: item.gross_weight,
                net_weight: item.net_weight,
                bobin: item.bobin,
                remark: item.remark
            }))
        };

        const response = await Axistance.post(`service/${serviceInfo.value.id}/convert-to-invoice`, dataToSend);
        
        if (response.status === 200 || response.status === 201) {
            swal(
                'Success!',
                response.data.message || 'Challan converted to invoice successfully',
                'success'
            );
            emit('invoice-converted');
            closeModal();
        }
    } catch (error) {
        console.error('Error converting to invoice:', error);
        swal(
            'Error!',
            error.response?.data?.message || 'Failed to convert challan to invoice',
            'error'
        );
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    $('#convertToInvoiceModal').modal('hide');
    emit('close-modal');
};
</script>

<style scoped>
.modal-xl {
    max-width: 95%;
}

.modal-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.modal-body {
    max-height: 80vh;
    overflow-y: auto;
}

.card {
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.35rem;
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    font-weight: 600;
}

.form-control {
    border-radius: 0.35rem;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 0.35rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

/* Custom scrollbar for modal body */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Animation for the modal */
@keyframes rotateInDownLeft {
    from {
        transform-origin: left bottom;
        transform: rotate3d(0, 0, 1, -45deg);
        opacity: 0;
    }
    to {
        transform-origin: left bottom;
        transform: rotate3d(0, 0, 1, 0deg);
        opacity: 1;
    }
}

.rotateInDownLeft {
    animation-name: rotateInDownLeft;
    animation-duration: 0.6s;
    animation-fill-mode: both;
}
</style>