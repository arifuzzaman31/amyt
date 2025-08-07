<template>
  <div class="layout-px-spacing">
    <div class="account-settings-container layout-top-spacing">

      <div class="account-content">
        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
          <form id="customer-stock-form" @submit.prevent="submitForm()">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="info section form-section-styled">
                  <div class="row">
                    <div class="col-md-11 mx-auto">
                      <h5 class="">Add Customer Stock</h5>
                      <div class="row">
                        <!-- Existing fields: purchase_date, supplier_id, challan_no -->
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="Stock_date">Date</label>
                            <input type="date" class="form-control mb-4" id="Stock_date"
                              v-model="customerItems.in_date" />
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <div>
                              <label for="customer_id">Select Customer</label>
                              <Select2 :settings="select2Settings" v-model="customerItems.customer_id"
                                @select="handleCustomerSelect" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="challan_no">Challan No</label>
                            <input type="text" class="form-control mb-4" id="challan_no"
                              v-model="customerItems.challan_no" />
                          </div>
                        </div>

                        <!-- New fields based on schema -->
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="document_link">Document (e.g., Invoice PDF)</label>
                            <!-- Using a simple file input for now. Dropify was here before, can be re-integrated if needed -->
                            <input type="file" class="form-control-file mb-4" id="document_link"
                              @change="handleFileUpload">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" placeholder="Description for the purchase order" rows="3"
                              v-model="customerItems.description"></textarea>
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
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="">Item List</h5>
                      <button type="button" class="btn btn-primary" @click="openModal">Add Item</button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" :class="{ 'show d-block': showModal }" tabindex="-1" role="dialog"
                      aria-labelledby="addItemModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="addItemModalLabel">Add Items</h5>
                            <button type="button" class="close" @click="showModal = false" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
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
                                  <tr v-for="(item, index) in customerItems.dataItem" :key="index">
                                    <td>
                                      <select v-model="item.yarn_count_id" class="form-control">
                                        <option value="">Select Yarn Count</option>
                                        <option v-for="yc in yarnCounts" :key="yc.id" :value="yc.id">
                                          {{ yc.name }} <!-- Assuming yarn count object has a 'name' property -->
                                        </option>
                                      </select>
                                    </td>
                                    <td><input v-model.number="item.quantity" class="form-control" type="number"
                                        step="0.01" placeholder="Quantity"></td>
                                    <td><input v-model.number="item.unit_price" class="form-control" type="number"
                                        step="0.01" placeholder="Unit Price"></td>
                                    <td>
                                      <button class="btn btn-danger btn-sm" @click="removeItem(index)">Remove</button>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <button type="button" class="btn btn-info mt-2" @click="addItem">Add Another Item</button>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="showModal = false">Close</button>
                            <button type="button" class="btn btn-primary" @click="saveItems">Done</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-backdrop fade show" v-if="showModal" @click="showModal = false"></div>
                    <!-- Display Added Items -->
                    <div class="work-section mt-3" v-if="hasAddedItems">
                      <h6>Added Items:</h6>
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-4">
                          <thead>
                            <tr>
                              <th>Yarn Count</th>
                              <th>Quantity</th>
                              <th>Unit Price</th>
                              <th>Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="addedItem in customerItems.dataItem" :key="addedItem.yarn_count_id">
                              <td>{{ getYarnCountName(addedItem.yarn_count_id) }}</td>
                              <td>{{ addedItem.quantity }}</td>
                              <td>{{ addedItem.unit_price }}</td>
                              <td>{{ (addedItem.quantity * addedItem.unit_price).toFixed(2) }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div v-else class="work-section mt-3">
                      <p>No items added yet.</p>
                    </div>

                    <!-- Totals and Status Section -->
                    <div class="row mt-4 justify-content-end" v-if="hasAddedItems">
                      <div class="col-md-5">
                        <div class="totals-section">
                          <div class="form-group row">
                            <label for="subtotal" class="col-sm-5 col-form-label text-right">Subtotal:</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control text-right" id="subtotal"
                                :value="subTotal.toFixed(2)">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="discount" class="col-sm-5 col-form-label text-right">Discount:</label>
                            <div class="col-sm-7">
                              <input type="number" class="form-control form-control-sm" id="discount"
                                v-model.number="customerItems.discount" placeholder="Discount value">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="discount_type" class="col-sm-5 col-form-label text-right">Discount Type:</label>
                            <div class="col-sm-7">
                              <select v-model="customerItems.discount_type" class="form-control form-control-sm"
                                id="discount_type">
                                <option value="">Select Type</option>
                                <option value="0">Percentage (%)</option>
                                <option value="1">Fixed Amount</option>
                              </select>
                            </div>
                          </div>
                          <hr>
                          <div class="form-group row">
                            <label for="discount_amount_display" class="col-sm-5 col-form-label text-right">Discount
                              Amount:</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control text-right" id="discount_amount_display"
                                :value="discountAmount.toFixed(2)">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="grand_total_display" class="col-sm-5 col-form-label text-right"><strong>Grand
                                Total:</strong></label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control text-right" id="grand_total_display"
                                :value="grandTotal.toFixed(2)" style="font-weight: bold;">
                            </div>
                          </div>
                          <hr>
                          <div class="form-group row">
                            <label for="payment_status_bottom" class="col-sm-5 col-form-label text-right">Payment
                              Status:</label>
                            <div class="col-sm-7">
                              <select v-model="customerItems.payment_status" class="form-control form-control-sm"
                                id="payment_status_bottom">
                                <option value="0">Due</option>
                                <option value="1">Paid</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="status_bottom" class="col-sm-5 col-form-label text-right">Order Status:</label>
                            <div class="col-sm-7">
                              <select v-model="customerItems.status" class="form-control form-control-sm"
                                id="status_bottom">
                                <!-- <option value="0">Pending</option> -->
                                <option value="1">Approved</option>
                                <!-- <option value="2">Rejected</option> -->
                                <option value="3">Draft</option>
                                <!-- <option value="4">Close</option> -->
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Totals and Status Section -->
                  </div>
                </div>
              </div>
              <div class="col-xl-4 offset-4 col-lg-4 col-md-4">
                <button type="submit" class="btn btn-success btn-block">Submit</button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import Axistance from '../../Axistance';
export default {
  setup() {
    const customers = ref([]);
    const yarnCounts = ref([]);
    const url = ref(rootUrl);
    const showModal = ref(false);

    const customerItems = reactive({
      customer_id: '',
      in_date: '',
      challan_no: '',
      document_link: null,
      total_amount: 0, // This will be updated by grandTotal watcher
      payment_status: 0,
      discount: 0,
      discount_type: '', // 0 for percentage, 1 for fixed
      status: 3,
      description: '',
      dataItem: [{ yarn_count_id: '', quantity: '', unit_price: '' }]
    });

    const openModal = (event) => {
      event.stopPropagation();
      showModal.value = true;
    };

    const addItem = () => {
      customerItems.dataItem.push({ yarn_count_id: '', quantity: '', unit_price: '' });
    };

    const removeItem = (index) => {
      if (customerItems.dataItem.length > 1) {
        customerItems.dataItem.splice(index, 1);
      } else {
        Object.assign(customerItems.dataItem[0], { yarn_count_id: '', quantity: '', unit_price: '' });
      }
    };
    const select2Settings = {
      ajax: {
        url: baseUrl + "customer",
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            q: params.term,
            page: params.page || 1,
          };
        },
        data: function (params) {
          return {
            page: params.page ?? 1,
            limit: 20,
            search: params.term || ''
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          const options = [];

          if (Array.isArray(data.data) && data.data.length > 0) {
            data.data.forEach(val => {
              options.push({
                id: val.id,
                text: `${val.name + '-' + val.customer_group?.name}`,
                product: val
              });
            });
          }

          return {
            results: options,
            pagination: {
              more: data.current_page >= data.last_page ? false : true,
            },
          };
        },
        cache: true,
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      minimumInputLength: 0,
      templateResult: formatProduct,
      templateSelection: formatProductSelection
    };
    function formatProduct(product) {
      if (product.loading) {
        return product.text;
      }
      return product.text;
    }

    function formatProductSelection(product) {
      return product.text || product.id;
    }

    const handleCustomerSelect = (customer) => {
      customerItems.customer_id = customer.id;
    };
    const saveItems = () => {
      showModal.value = false;
    };

    const getCustomer = async () => {
      try {
        const response = await Axistance.get('customer');
        customers.value = response.data;
      } catch (error) {
        console.error('Error fetching customers:', error);
      }
    };

    const getYarnCounts = async () => {
      try {
        const response = await Axistance.get('yarn-count');
        yarnCounts.value = response.data.data;
      } catch (error) {
        console.error('Error fetching yarn counts:', error);
      }
    };

    const handleFileUpload = (event) => {
      const file = event.target.files[0];
      if (file) {
        customerItems.document_link = file;
      }
    };

    const getYarnCountName = (id) => {
      const found = yarnCounts.value.find(yc => yc.id === id);
      return found ? found.name : 'N/A';
    };

    // Computed Properties for Totals
    const subTotal = computed(() => {
      return customerItems.dataItem.reduce((acc, item) => {
        const quantity = parseFloat(item.quantity) || 0;
        const price = parseFloat(item.unit_price) || 0;
        return acc + (quantity * price);
      }, 0);
    });

    const discountAmount = computed(() => {
      const discountValue = parseFloat(customerItems.discount) || 0;
      if (customerItems.discount_type === '0') { // Percentage
        return (subTotal.value * discountValue) / 100;
      } else if (customerItems.discount_type === '1') { // Fixed
        return discountValue;
      }
      return 0;
    });

    const grandTotal = computed(() => {
      return subTotal.value - discountAmount.value;
    });

    const hasAddedItems = computed(() => {
      // An item is considered "added" or "meaningful" if it has a yarn_count_id selected.
      // This aligns with the logic for showing the "Added Items" table and
      // handles the fact that dataItem always has at least one (potentially blank) entry.
      return customerItems.dataItem.some(item => item.yarn_count_id);
    });

    // Watch grandTotal to update customerItems.total_amount
    watch(grandTotal, (newTotal) => {
      customerItems.total_amount = newTotal;
    });

    const submitForm = async () => {
      console.log('Submitting Customer Stock record:', customerItems);
      const formData = new FormData();
      Object.keys(customerItems).forEach(key => {
        if (key === 'dataItem') {
          formData.append(key, JSON.stringify(customerItems[key]));
        } else if (key === 'document_link' && customerItems[key] instanceof File) {
          formData.append(key, customerItems[key], customerItems[key].name);
        } else {
          formData.append(key, customerItems[key]);
        }
      });

      try {
        const response = await Axistance.post('customer-stock-in', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
        alert(response.data.message || 'Customer Stock record created successfully!');
        console.log(response.data);
        resetForm();
        window.location.href = 'customer-stock-list';

      } catch (error) {
        alert(error.response?.data?.message || 'An error occurred');
      }
    };

    const resetForm = () => {
      customerItems.dataItem = [{ yarn_count_id: '', quantity: '', unit_price: '' }];
      customerItems.customer_id = '';
      customerItems.in_date = '';
      customerItems.challan_no = '';
      customerItems.document_link = null;
      customerItems.total_amount = 0;
      customerItems.payment_status = 0;
      customerItems.discount = 0;
      customerItems.discount_type = '';
      customerItems.status = 3;
      customerItems.description = '';
      showModal.value = false; // Close the modal after resetting
    };

    onMounted(() => {
      getCustomer();
      getYarnCounts();
    });

    return {
      customers,
      yarnCounts,
      url,
      customerItems,
      openModal,
      addItem,
      removeItem,
      saveItems,
      select2Settings,
      handleCustomerSelect,
      getCustomer,
      handleFileUpload,
      getYarnCountName,
      submitForm,
      showModal,
      subTotal,        // Expose computed properties
      discountAmount,
      grandTotal,
      hasAddedItems    // Expose new computed property
    };
  }
};
</script>

<style scoped>
.form-section-styled {
  background-color: #fdfdfd;
  /* Slightly off-white for brightness */
  padding: 25px;
  border-radius: 8px;
  border: 1px solid #e0e6ed;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 30px;
}

.form-section-styled h5 {
  margin: 10px 0px 20px;
  color: #3b3f5c;
}

/* Adjust modal backdrop to ensure it's behind the modal if z-index issues arise */
.modal-backdrop {
  z-index: 1040;
  /* Default Bootstrap backdrop z-index */
}

.modal {
  z-index: 1050;
  /* Default Bootstrap modal z-index */
}

/* Add some spacing to form groups within the styled sections for better readability */
.form-section-styled .form-group {
  margin-bottom: 1.5rem;
  /* Increased bottom margin for form groups */
}

/* Ensure labels are clear */
.form-section-styled label {
  font-weight: 600;
  color: #3b3f5c;
}

/* Style the table within the item list section for consistency */
.item-list-section .table {
  margin-top: 15px;
}

.totals-section .form-group label {
  font-weight: 500;
  /* Slightly less bold for totals section labels */
}

.totals-section .form-control-plaintext {
  padding-top: 0.375rem;
  /* Align with form-control-sm */
  padding-bottom: 0.375rem;
}

.totals-section hr {
  margin-top: 1rem;
  margin-bottom: 1rem;
}
</style>