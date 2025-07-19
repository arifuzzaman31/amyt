<template>
    <div class="modal animated rotateInDownLeft custo-rotateInDownLeft show" id="editServiceModal" tabindex="-1"
        role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="statbox widget box box-shadow">
                        <form id="service-form" @submit.prevent="submitUpdateForm()">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="info section form-section-styled">
                                        <div class="row">
                                            <div class="col-md-11 mx-auto">
                                                <h5 class="">Service Details</h5>
                                                <div class="row">
                                                    <!-- Existing fields: service_date, customer_id, invoice_no -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="service_date">Service Date</label>
                                                            <input type="date" class="form-control mb-4"
                                                                id="service_date" v-model="serviceInfo.service_date" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="customer_id">Select Customer</label>
                                                            <select v-model="serviceInfo.customer_id"
                                                                class="form-control mb-4" id="customer_id">
                                                                <option value="">Select Customer</option>
                                                                <option v-for="s in customers" :key="s.id"
                                                                    :value="s.id">
                                                                    {{ s.name }} - {{ s.company_name }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="invoice_no">Invoice No</label>
                                                            <input type="text" class="form-control mb-4" id="invoice_no"
                                                                v-model="serviceInfo.invoice_no" />
                                                        </div>
                                                    </div>

                                                    <!-- New fields based on schema -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="document_link">Document (e.g., Invoice
                                                                PDF)</label>
                                                            <!-- Using a simple file input for now. Dropify was here before, can be re-integrated if needed -->
                                                            <input type="file" class="form-control-file mb-4"
                                                                id="document_link" @change="handleFileUpload">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea class="form-control"
                                                                placeholder="Description for the service order" rows="3"
                                                                v-model="serviceInfo.description"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <!-- Item List Section -->
                                    <div class="section item-list-section form-section-styled">
                                        <div class="info">
                                            <!-- Display Added Items -->
                                            <div class="work-section mt-3">
                                                <h6>Added Items:</h6>
                                                <div class="table-responsive" style="max-width: 99%; overflow-x: auto;">
                                                    <table class="table table-bordered table-striped mb-4" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Yarn Count</th>
                                                                <th>Color</th>
                                                                <th>Quantity</th>
                                                                <th>Extra Quantity</th>
                                                                <th>Gross Weight</th>
                                                                <th>Net Weight</th>
                                                                <th>Bobin</th>
                                                                <th>Remark</th>
                                                                <th>Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(addedItem, index) in serviceInfo.dataItem"
                                                                :key="index">
                                                                <td>
                                                                    <select v-model="addedItem.yarn_count_id"
                                                                        class="form-control">
                                                                        <option value="">Select Yarn Count</option>
                                                                        <option v-for="yc in yarnCounts" :key="yc.id"
                                                                            :value="yc.id">
                                                                            {{ yc.name }}
                                                                            <!-- Assuming yarn count object has a 'name' property -->
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select v-model="addedItem.color_id"
                                                                        class="form-control">
                                                                        <option value="">Select Color</option>
                                                                        <option v-for="attr in attributes?.color"
                                                                            :key="attr.id" :value="attr.id">
                                                                            {{ attr.name }}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input v-model.number="addedItem.quantity"
                                                                        class="form-control form-control-sm"
                                                                        type="number" placeholder="Quantity" />
                                                                    <select v-model="addedItem.unit_attr_id"
                                                                        class="form-control form-control-sm my-1">
                                                                        <option value="">Select Unit</option>
                                                                        <option v-for="attr in attributes?.weight"
                                                                            :key="attr.id" :value="attr.id">
                                                                            {{ attr.name }}
                                                                        </option>
                                                                    </select>
                                                                    <input v-model.number="addedItem.unit_price"
                                                                        class="form-control form-control-sm"
                                                                        type="number" placeholder="Unit Price" />
                                                                </td>
                                                                <td>
                                                                    <input v-model.number="addedItem.extra_quantity"
                                                                        class="form-control" type="number"
                                                                        placeholder="Extra Quantity">
                                                                    <input
                                                                        v-model.number="addedItem.extra_quantity_price"
                                                                        class="form-control form-control-sm my-1"
                                                                        type="number"
                                                                        placeholder="Extra Quantity Price">
                                                                </td>
                                                                <td>
                                                                    <input v-model.number="addedItem.gross_weight"
                                                                        class="form-control form-control-sm"
                                                                        type="number" placeholder="Gross Weight">
                                                                    <select v-model="addedItem.weight_attr_id"
                                                                        class="form-control form-control-sm my-1">
                                                                        <option value="">Select Unit</option>
                                                                        <option v-for="attr in attributes?.weight"
                                                                            :key="attr.id" :value="attr.id">
                                                                            {{ attr.name }}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input v-model.number="addedItem.net_weight"
                                                                        class="form-control form-control-sm"
                                                                        type="number" placeholder="Net Weight">
                                                                    <select v-model="addedItem.weight_attr_id"
                                                                        class="form-control form-control-sm my-1">
                                                                        <option value="">Select Unit</option>
                                                                        <option v-for="attr in attributes?.weight"
                                                                            :key="attr.id" :value="attr.id">
                                                                            {{ attr.name }}
                                                                        </option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input v-model.number="addedItem.bobin"
                                                                        class="form-control" type="number"
                                                                        placeholder="Bobin">
                                                                </td>
                                                                <td>
                                                                    <input v-model.number="addedItem.remark"
                                                                        class="form-control" type="text"
                                                                        placeholder="Remark">
                                                                </td>
                                                                <td><a href="javascript:void(0)" type="button"
                                                                        class="btn btn-danger btn-sm"
                                                                        @click.prevent="removeItem(index)"><svg
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            fill="none" stroke="currentColor"
                                                                            stroke-width="2" stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="feather feather-trash-2">
                                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                                            <path
                                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                            </path>
                                                                            <line x1="10" y1="11" x2="10" y2="17">
                                                                            </line>
                                                                            <line x1="14" y1="11" x2="14" y2="17">
                                                                            </line>
                                                                        </svg></a>
                                                                </td>

                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="font-weight-bold">
                                                                <td colspan="2" class="text-right">Total:</td>
                                                                <td>{{ totalQuantity }}/{{
                                                                    getAttrName(serviceInfo.dataItem[0].unit_attr_id,
                                                                        'weight') }}</td>
                                                                <td>{{ totalExtraQuantity }}</td>
                                                                <td>{{ totalGrossWeight }} {{
                                                                    getAttrName(serviceInfo.dataItem[0].weight_attr_id,
                                                                        'weight') }}</td>
                                                                <td>{{ totalNetWeight }} {{
                                                                    getAttrName(serviceInfo.dataItem[0].weight_attr_id,
                                                                        'weight') }}</td>
                                                                <td>{{ totalBobin }}</td>
                                                                <td colspan="2"></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <button type="button" class="btn btn-info mt-2"
                                                    @click.prevent="addItem">Add Another
                                                    Item</button>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 offset-4 col-lg-4 col-md-4">
                                    <button type="submit" class="btn btn-success btn-block">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, computed, nextTick } from 'vue';
import Axistance from '../../Axistance';

const props = defineProps({
    service: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['service-updated', 'close-modal']);
const yarnCounts = ref([]);
const customers = ref([]);
const attributes = ref([]);
const serviceInfo = ref({
    customer_id: '',
    service_date: '',
    invoice_no: '',
    document_link: null,
    total_amount: 0,
    payment_status: 0,
    discount: 0,
    discount_type: 0,
    status: 0,
    description: '',
    dataItem: [] // Initialize dataItem as an empty array
});

watch(() => props.service, (newVal) => {
    if (newVal) {
        serviceInfo.value = {
            ...newVal, dataItem: newVal.items && newVal.items.length ? newVal.items : [{
                yarn_count_id: '', unit_attr_id: '', quantity: 0, unit_price: 0,
                extra_quantity: 0, extra_quantity_price: 0, color_id: '', gross_weight: 0,
                net_weight: 0, weight_attr_id: '', bobin: 0, remark: ''
            }]
        };
        nextTick(() => {
            $('#editServiceModal').modal('show');
        });
    }
}, { immediate: true, deep: true });

const getAttribute = async () => {
    try {
        const response = await Axistance.get('attribute'); // Ensure this endpoint is correct
        const grouped = response.data;
        attributes.value = grouped.reduce((acc, item) => {
            if (!acc[item.type]) {
                acc[item.type] = [];
            }
            acc[item.type].push(item);
            return acc;
        }, {});
        // console.log('Attributes fetched:', attributes.value);
    } catch (error) {
        console.error('Error fetching yarn counts:', error);
    }
};


const getAttrName = (id, attr) => {
    const found = attributes.value?.[attr]?.find(yc => yc.id == id);
    return found ? found.name : 'N/A';
};

const getCustomer = async () => {
    try {
        const response = await Axistance.get('customer');
        customers.value = response.data;
    } catch (error) {
        console.error('Error fetching customer:', error);
    }
};

const addItem = () => {
    serviceInfo.value.dataItem.push({
        yarn_count_id: '', unit_attr_id: '', quantity: 0, unit_price: 0,
        extra_quantity: 0, extra_quantity_price: 0, color_id: '', gross_weight: 0,
        net_weight: 0, weight_attr_id: '', bobin: 0, remark: ''
    });
};

const removeItem = (index) => {
    if (serviceInfo.value.dataItem.length > 1) {
        serviceInfo.value.dataItem.splice(index, 1);
    }
};

const submitUpdateForm = async () => {
    const response = await Axistance.put(`service/${serviceInfo.value.id}`, serviceInfo.value);
    if (response.status !== 201) {
        console.error('Error updating service:', response);
        return;
    }

    serviceInfo.value = {
        customer_id: '',
        service_date: '',
        invoice_no: '',
        document_link: null,
        total_amount: 0,
        payment_status: 0,
        discount: 0,
        discount_type: 0,
        status: 0,
        description: '',
        dataItem: [{
            yarn_count_id: '', unit_attr_id: '', quantity: 0, unit_price: 0,
            extra_quantity: 0, extra_quantity_price: 0, color_id: '', gross_weight: 0,
            net_weight: 0, weight_attr_id: '', bobin: 0, remark: ''
        }]
    };
    emit('service-updated');
    closeModal();
};

const closeModal = () => {
    $('#editServiceModal').modal('hide');
    emit('close-modal');
};

const getYarnCounts = async () => {
    try {
        const response = await Axistance.get(baseUrl + 'yarn-count');
        yarnCounts.value = response.data.data;
    } catch (error) {
        console.error('Error fetching yarn counts:', error);
    }
};

const totalQuantity = computed(() => {
    return (serviceInfo.value.dataItem || []).reduce((sum, item) => sum + (Number(item.quantity) || 0), 0);
});

const totalExtraQuantity = computed(() => {
    return (serviceInfo.value.dataItem || []).reduce((sum, item) => sum + (Number(item.extra_quantity) || 0), 0);
});

const totalGrossWeight = computed(() => {
    return (serviceInfo.value.dataItem || []).reduce((sum, item) => sum + (Number(item.gross_weight) || 0), 0);
});

const totalNetWeight = computed(() => {
    return (serviceInfo.value.dataItem || []).reduce((sum, item) => sum + (Number(item.net_weight) || 0), 0);
});

const totalBobin = computed(() => {
    return (serviceInfo.value.dataItem || []).reduce((sum, item) => sum + (Number(item.bobin) || 0), 0);
});


onMounted(() => {
    getYarnCounts();
    getCustomer();
    getAttribute();
    $('#editServiceModal').on('hidden.bs.modal', () => {
        emit('close-modal');
    });
});
</script>
<style scoped>
.modal-xl {
    max-width: 90%;
}
</style>