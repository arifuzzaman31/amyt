<template>
  <div class="layout-px-spacing">
    <div class="account-settings-container layout-top-spacing">

      <div class="account-content">
        <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
          <form id="service-form" @submit.prevent="submitForm()">
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
                            <input type="date" class="form-control mb-4" id="service_date"
                              v-model="serviceInfo.service_date" />
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="customer_id">Select Customer</label>
                            <select v-model="serviceInfo.customer_id" class="form-control mb-4" id="customer_id"
                              @change="getCustomerYarnCounts">
                              <option value="">Select Customer</option>
                              <option v-for="s in customers" :key="s.id" :value="s.id">
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
                            <label for="document_link">Document (e.g., Invoice PDF)</label>
                            <!-- Using a simple file input for now. Dropify was here before, can be re-integrated if needed -->
                            <input type="file" class="form-control-file mb-4" id="document_link"
                              @change="handleFileUpload">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" placeholder="Description for the service order" rows="3"
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
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="">Item List</h5>
                      <button type="button" class="btn btn-primary" @click="openModal">Add Item</button>
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
                              <th>Color</th>
                              <th>Quantity</th>
                              <th>Extra Quantity</th>
                              <th>Gross Weight</th>
                              <th>Net Weight</th>
                              <th>Bobin</th>
                              <th>Remark</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="addedItem in serviceInfo.dataItem" :key="addedItem.yarn_count_id">
                              <td>{{ getYarnCountName(addedItem.yarn_count_id) }}</td>
                              <td>{{ getAttrName(addedItem.color_id, 'color') }}</td>
                              <td>{{ addedItem.quantity }} {{ getAttrName(addedItem.unit_attr_id, 'weight') }}</td>
                              <td>{{ addedItem.extra_quantity }}</td>
                              <td>{{ addedItem.gross_weight }} {{ getAttrName(addedItem.weight_attr_id, 'weight') }}
                              </td>
                              <td>{{ addedItem.net_weight }} {{ getAttrName(addedItem.weight_attr_id, 'weight') }}</td>
                              <td>{{ addedItem.bobin }}</td>
                              <td class="text-wrap">{{ addedItem.remark }}</td>
                            </tr>
                          </tbody>
                          <tfoot>
                            <tr class="font-weight-bold">
                              <td colspan="2" class="text-right">Total:</td>
                              <td>{{ totalQuantity }}/{{ getAttrName(serviceInfo.dataItem[0].unit_attr_id, 'weight') }}
                              </td>
                              <td>{{ totalExtraQuantity }}</td>
                              <td>{{ totalGrossWeight }} {{ getAttrName(serviceInfo.dataItem[0].weight_attr_id,
                                'weight') }}</td>
                              <td>{{ totalNetWeight }} {{ getAttrName(serviceInfo.dataItem[0].weight_attr_id, 'weight')
                              }}</td>
                              <td>{{ totalBobin }}</td>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                    <div v-else class="work-section mt-3">
                      <p>No items added yet.</p>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-xl-4 offset-4 col-lg-4 col-md-4">
                <button type="submit" class="btn btn-success btn-block">Submit</button>
              </div>
            </div>
          </form>
          <!-- Modal -->
          <div class="modal animated fadeInRight custo-fadeInRight show" :class="{ 'show d-block': showModal }"
            tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addItemModalLabel">Add/Edit Items</h5>
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
                          <th>Color</th>
                          <th>Quantity</th>
                          <th>Extra Quantity</th>
                          <th>Gross Weight</th>
                          <th>Net Weight</th>
                          <th>Bobin</th>
                          <th>Remark</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(item, index) in serviceInfo.dataItem" :key="index">
                          <td>
                              <small style="font-size: 12px; color: #555;">{{ getYarnQuantity(item) }}</small>
                              <select v-model="item.yarn_count_id" class="form-control">
                                <option value="">Select Yarn Count</option>
                                <option v-for="yc in yarnCounts" :key="yc.id" :value="yc.id">
                                  {{ yc.name }}
                                </option>
                              </select>
                          </td>
                          <td>
                            <select v-model="item.color_id" class="form-control">
                              <option value="">Select Color</option>
                              <option v-for="attr in attributes?.color" :key="attr.id" :value="attr.id">
                                {{ attr.name }}
                              </option>
                            </select>
                          </td>
                          <td><input v-model.number="item.quantity" class="form-control form-control-sm" type="number"
                              placeholder="Quantity">
                            <select v-model="item.unit_attr_id" class="form-control form-control-sm my-1">
                              <option value="">Select Unit</option>
                              <option v-for="attr in attributes?.weight" :key="attr.id" :value="attr.id">
                                {{ attr.name }}
                              </option>
                            </select>
                            <input v-model.number="item.unit_price" class="form-control form-control-sm" type="number"
                              placeholder="Unit Price">
                          </td>
                          <td><input v-model.number="item.extra_quantity" class="form-control" type="number"
                              placeholder="Extra Quantity">
                            <input v-model.number="item.extra_quantity_price" class="form-control form-control-sm mt-1"
                              type="number" placeholder="Extra Quantity Price">
                          </td>
                          <td><input v-model.number="item.gross_weight" class="form-control form-control-sm"
                              type="number" placeholder="Gross Weight">
                            <select v-model="item.weight_attr_id" class="form-control form-control-sm my-1">
                              <option value="">Select Unit</option>
                              <option v-for="attr in attributes?.weight" :key="attr.id" :value="attr.id">
                                {{ attr.name }}
                              </option>
                            </select>
                          </td>
                          <td><input v-model.number="item.net_weight" class="form-control form-control-sm" type="number"
                              placeholder="Net Weight">
                            <select v-model="item.weight_attr_id" class="form-control form-control-sm my-1">
                              <option value="">Select Unit</option>
                              <option v-for="attr in attributes?.weight" :key="attr.id" :value="attr.id">
                                {{ attr.name }}
                              </option>
                            </select>
                          </td>
                          <td><input v-model.number="item.bobin" class="form-control" type="number" placeholder="Bobin">
                          </td>
                          <td><input v-model.number="item.remark" class="form-control" type="text" placeholder="Remark">
                          </td>
                          <td>
                            <a href="javascript:void(0)" type="button" class="btn btn-danger btn-sm"
                              @click="removeItem(index)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                  d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                              </svg></a>
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted, computed, watch } from 'vue'; // Added computed and watch
import Axistance from '../../Axistance';
export default {
  setup() {
    const customers = ref([]);
    const yarnCounts = ref([]);
    const attributes = ref([]);
    const customerYarnCounts = ref([]);
    const showModal = ref(false);

    const serviceInfo = reactive({
      customer_id: '',
      service_date: '',
      invoice_no: '',
      document_link: null,
      total_amount: 0, // This will be upservice_dated by grandTotal watcher
      payment_status: 0,
      discount: 0,
      discount_type: 0, // 0 for percentage, 1 for fixed
      status: 0,
      description: '',
      dataItem: [{
        yarn_count_id: '', unit_attr_id: '', quantity: 0, unit_price: 0,
        extra_quantity: 0, extra_quantity_price: 0, color_id: '', gross_weight: 0,
        net_weight: 0, weight_attr_id: '', bobin: '', remark: ''
      }]
    });

    const getCustomerYarnCounts = () => {
      // Fetch yarn counts for the selected customer from customer stock
      // resetForm();
      if (serviceInfo.customer_id) {
        Axistance.get(`customer/${serviceInfo.customer_id}`)
          .then(response => {
            customerYarnCounts.value = response.data?.customer_stock;
          })
          .catch(error => {
            console.error('Error fetching yarn counts for customer:', error);
          });
      } else {
        yarnCounts.value = [];
      }
    };

    const openModal = (event) => {
      event.stopPropagation();
      showModal.value = true;
    };

    const getYarnQuantity = (item) => {
      const yarnCount = customerYarnCounts.value.find(yc => yc.yarn_count_id == item.yarn_count_id);
      const foundYarn = yarnCounts.value.find(yc => yc.id == item.yarn_count_id);
      const amytStock = foundYarn?.amyt_stock?.quantity ?? 0;
      return `client:${yarnCount?.quantity ?? 0},amyt:${amytStock}`;
    };

    const addItem = () => {
      serviceInfo.dataItem.push({
        yarn_count_id: '', unit_attr_id: '', quantity: 0, unit_price: 0,
        extra_quantity: 0, extra_quantity_price: 0, color_id: '', gross_weight: 0,
        net_weight: 0, weight_attr_id: '', bobin: '', remark: ''
      });
    };

    const removeItem = (index) => {
      if (serviceInfo.dataItem.length > 1) {
        serviceInfo.dataItem.splice(index, 1);
      } else {
        Object.assign(serviceInfo.dataItem[0], {
          yarn_count_id: '', unit_attr_id: '', quantity: 0, unit_price: 0,
          extra_quantity: 0, extra_quantity_price: 0, color_id: '', gross_weight: 0,
          net_weight: 0, weight_attr_id: '', bobin: '', remark: ''
        });
      }
    };

    const saveItems = () => {
      showModal.value = false;
    };

    const getCustomer = async () => {
      try {
        const response = await Axistance.get('customer');
        customers.value = response.data;
      } catch (error) {
        console.error('Error fetching customer:', error);
      }
    };

    const getYarnCounts = async () => {
      try {
        const response = await Axistance.get('yarn-count?isPaginate=no&relation[]=amytStock'); // Ensure this endpoint is correct
        yarnCounts.value = response.data;
      } catch (error) {
        console.error('Error fetching yarn counts:', error);
      }
    };

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
    const handleFileUpload = (event) => {
      const file = event.target.files[0];
      if (file) {
        serviceInfo.document_link = file;
      }
    };

    const getYarnCountName = (id) => {
      const found = yarnCounts.value.find(yc => yc.id == id);
      return found ? found.name : 'N/A';
    };

    const getAttrName = (id, attr) => {
      const found = attributes.value?.[attr]?.find(yc => yc.id == id);
      return found ? found.name : 'N/A';
    };

    // Computed Properties for Totals
    const totalQuantity = computed(() =>
      serviceInfo.dataItem.reduce((sum, item) => sum + (item.quantity || 0), 0)
    );

    const totalExtraQuantity = computed(() =>
      serviceInfo.dataItem.reduce((sum, item) => sum + (item.extra_quantity || 0), 0)
    );

    const totalGrossWeight = computed(() =>
      serviceInfo.dataItem.reduce((sum, item) => sum + (item.gross_weight || 0), 0)
    );

    const totalNetWeight = computed(() =>
      serviceInfo.dataItem.reduce((sum, item) => sum + (item.net_weight || 0), 0)
    );

    const totalBobin = computed(() =>
      serviceInfo.dataItem.reduce((sum, item) => sum + (item.bobin || 0), 0)
    );
    const hasAddedItems = computed(() => {
      return serviceInfo.dataItem.some(item => item.yarn_count_id);
    });

    const submitForm = async () => {
      // console.log('Submitting Service Order:', serviceInfo);
      const formData = new FormData();
      Object.keys(serviceInfo).forEach(key => {
        if (key == 'dataItem') {
          formData.append(key, JSON.stringify(serviceInfo[key]));
        } else if (key == 'document_link' && serviceInfo[key] instanceof File) {
          formData.append(key, serviceInfo[key], serviceInfo[key].name);
        } else {
          formData.append(key, serviceInfo[key]);
        }
      });

      try {
        const response = await Axistance.post('service', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
        alert(response.data.message);
        resetForm();
        //redirect to service list
        window.location.href = 'service-list';

      } catch (error) {
        alert(error.response?.data?.message || 'An error occurred');
      }
    };

    const resetForm = () => {
      serviceInfo.dataItem = [{
        yarn_count_id: '', unit_attr_id: '', quantity: 0, unit_price: 0,
        extra_quantity: 0, extra_quantity_price: 0, color_id: '', gross_weight: 0,
        net_weight: 0, weight_attr_id: '', bobin: '', remark: ''
      }];
      serviceInfo.customer_id = '';
      serviceInfo.service_date = '';
      serviceInfo.invoice_no = '';
      serviceInfo.document_link = null;
      serviceInfo.total_amount = 0;
      serviceInfo.payment_status = 0;
      serviceInfo.discount = 0;
      serviceInfo.discount_type = 0;
      serviceInfo.status = 0;
      serviceInfo.description = '';
      showModal.value = false; // Close the modal after resetting
    };

    onMounted(() => {
      getCustomer();
      getYarnCounts();
      getAttribute();
    });

    return {
      customers,
      yarnCounts,
      serviceInfo,
      attributes,
      openModal,
      addItem,
      removeItem,
      saveItems,
      getCustomer,
      handleFileUpload,
      getYarnCountName,
      getAttrName,
      getCustomerYarnCounts,
      submitForm,
      getYarnQuantity,
      showModal,
      customerYarnCounts,
      totalQuantity,
      totalExtraQuantity,
      totalGrossWeight,
      totalNetWeight,
      totalBobin,
      hasAddedItems    // Expose new computed property
    };
  }
};
</script>

<style scoped>
.modal-xl {
  max-width: 90%;
}

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