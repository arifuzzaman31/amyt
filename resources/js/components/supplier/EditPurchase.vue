<template>
    <div class="modal fade" id="editPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="editPurchaseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPurchaseModalLabel">Edit Purchase</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="statbox widget box box-shadow">
                        <form @submit.prevent="submitForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_date">Purchase Date</label>
                                        <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                                            v-model="form.purchase_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="challan_no">Challan No</label>
                                        <input type="text" class="form-control" id="challan_no" name="challan_no" v-model="form.challan_no">
                                    </div>
                                </div>
                            </div>

                            <div class="section item-list-section form-section-styled">
                                <div class="info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="">Item List</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Yarn Count</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(item, index) in form.items" :key="index">
                                                    <td>
                                                        <select v-model="item.yarn_count_id" class="form-control" :id="'item_yarn_count_' + index" :name="'items[' + index + '][yarn_count_id]'">
                                                            <option value="">Select Yarn Count</option>
                                                            <option v-for="yc in yarnCounts" :key="yc.id" :value="yc.id">
                                                                {{ yc.name }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td><input v-model.number="item.quantity" class="form-control"
                                                        :id="'updateQty'+index" :name="'items[' + index + '][quantity]'" type="number" placeholder="Quantity"></td>
                                                    <td><input v-model.number="item.unit_price" class="form-control"
                                                        :id="'updateUnitPrice'+index" :name="'items[' + index + '][unit_price]'" type="number" placeholder="Unit Price"></td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm"
                                                            @click="removeItem(index)">Remove</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-info mt-2" @click="addItem">Add Another
                                        Item</button>
                                </div>
                            </div>

                            <div class="row mt-4 justify-content-end">
                                <div class="col-md-5">
                                    <div class="totals-section">
                                        <div class="form-group row">
                                            <label for="subtotal"
                                                class="col-sm-5 col-form-label text-right">Subtotal:</label>
                                            <div class="col-sm-7">
                                                <input type="text" readonly class="form-control text-right" id="subtotal"
                                                    :value="subTotal.toFixed(2)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="discount"
                                                class="col-sm-5 col-form-label text-right">Discount:</label>
                                            <div class="col-sm-7">
                                                <input type="number" class="form-control form-control-sm" id="discount" name="discount"
                                                    v-model.number="form.discount" placeholder="Discount value">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="discount_type"
                                                class="col-sm-5 col-form-label text-right">Discount Type:</label>
                                            <div class="col-sm-7">
                                                <select v-model="form.discount_type" class="form-control form-control-sm" name="discount_type"
                                                    id="discount_type">
                                                    <option :value="null">Select Type</option>
                                                    <option value="0">Percentage (%)</option>
                                                    <option value="1">Fixed Amount</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="discount_amount_display"
                                                class="col-sm-5 col-form-label text-right">Discount
                                                Amount:</label>
                                            <div class="col-sm-7">
                                                <input type="text" readonly class="form-control text-right"
                                                    id="discount_amount_display" :value="discountAmount.toFixed(2)">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="grand_total_display"
                                                class="col-sm-5 col-form-label text-right"><strong>Grand
                                                    Total:</strong></label>
                                            <div class="col-sm-7">
                                                <input type="text" readonly class="form-control text-right"
                                                    id="grand_total_display" :value="grandTotal.toFixed(2)"
                                                    style="font-weight: bold;">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label for="payment_status_bottom"
                                                class="col-sm-5 col-form-label text-right">Payment
                                                Status:</label>
                                            <div class="col-sm-7">
                                                <select v-model="form.payment_status" class="form-control form-control-sm" name="payment_status"
                                                    id="payment_status_bottom">
                                                    <option value="0">Due</option>
                                                    <option value="1">Paid</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="status_bottom" class="col-sm-5 col-form-label text-right">Order
                                                Status:</label>
                                            <div class="col-sm-7">
                                                <select v-model="form.status" class="form-control form-control-sm" name="status"
                                                    id="status_bottom">
                                                    <option value="1">Approved</option>
                                                    <option value="3">Draft</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
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
    purchase: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['purchase-updated', 'close-modal']);
const yarnCounts = ref([]);

const form = ref({
    id: '',
    purchase_date: '',
    challan_no: '',
    total_amount: 0,
    payment_status: 0,
    status: 1,
    discount: 0,
    discount_type: null,
    items: []
});

watch(() => props.purchase, (newVal) => {
    if (newVal) {
        form.value = { ...newVal, items: newVal.items && newVal.items.length ? newVal.items : [{ yarn_count_id: '', quantity: '', unit_price: '' }] };
        nextTick(() => {
            $('#editPurchaseModal').modal('show');
        });
    }
}, { immediate: true, deep: true });

const subTotal = computed(() => {
    return form.value.items.reduce((acc, item) => {
        const quantity = parseFloat(item.quantity) || 0;
        const price = parseFloat(item.unit_price) || 0;
        return acc + (quantity * price);
    }, 0);
});

const discountAmount = computed(() => {
    const discountValue = parseFloat(form.value.discount) || 0;
    if (form.value.discount_type == 0) { // Percentage
        return (subTotal.value * discountValue) / 100;
    } else if (form.value.discount_type == 1) { // Fixed
        return discountValue;
    }
    return 0;
});

const grandTotal = computed(() => {
    return subTotal.value - discountAmount.value;
});

watch(grandTotal, (newTotal) => {
    form.value.total_amount = newTotal;
});


const addItem = () => {
    form.value.items.push({ yarn_count_id: '', quantity: '', unit_price: '' });
};

const removeItem = (index) => {
    if (form.value.items.length > 1) {
        form.value.items.splice(index, 1);
    }
};

const submitForm = async () => {
    await Axistance.put(baseUrl + `purchase/${form.value.id}`, form.value);
    emit('purchase-updated');
    closeModal();
};

const closeModal = () => {
    $('#editPurchaseModal').modal('hide');
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

onMounted(() => {
    getYarnCounts();
    $('#editPurchaseModal').on('hidden.bs.modal', () => {
        emit('close-modal');
    });
});
</script>