<template>
  <div class="container">
    <h2 class="mt-4">Add Order</h2>
    <form @submit.prevent="submitForm" class="needs-validation" novalidate>
      <div class="mb-3">
        <label for="orderNumber" class="form-label">Order Number</label>
        <input type="text" class="form-control" id="orderNumber" value="Auto Generated" readonly>
      </div>
      <div class="mb-3">
        <label for="supplier" class="form-label">Supplier*</label>
        <select class="form-select" id="supplier" v-model="supplier" required>
          <option selected disabled value="">Select Supplier</option>
          <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.name">{{ supplier.name }}</option>
        </select>
        <div class="invalid-feedback">
          Please select a supplier.
        </div>
      </div>
      <div class="mb-3">
        <label for="date" class="form-label">Date*</label>
        <input type="date" class="form-control" id="date" v-model="date" required>
        <div class="invalid-feedback">
          Please select a date.
        </div>
      </div>
      <div class="mb-3">
        <label for="receiptNumber" class="form-label">Supplier Receipt Number</label>
        <input type="text" class="form-control" id="receiptNumber" v-model="receiptNumber">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" v-model="description"></textarea>
      </div>

      <h4 class="mt-4">Items*</h4>
      <div class="item-list">
        <div v-for="(item, index) in items" :key="index" class="mb-3 border p-3">
          <label for="item" class="form-label">ITEM*</label>
          <select class="form-select" v-model="item.name" required>
            <option selected disabled value="">Select Item</option>
            <option v-for="item in allItems" :key="item.id" :value="item.name">{{ item.name }}</option>
          </select>
          <div class="invalid-feedback">
            Please select an item.
          </div>

          <label for="needByDate" class="form-label">Need By Date</label>
          <input type="date" class="form-control" v-model="item.needByDate">

          <div class="row">
            <div class="col-md-3">
              <label for="unit" class="form-label">Unit*</label>
              <input type="text" class="form-control" v-model="item.unit" required>
              <div class="invalid-feedback">
                Please provide a unit.
              </div>
            </div>
            <div class="col-md-3">
              <label for="quantity" class="form-label">Quantity*</label>
              <input type="number" class="form-control" v-model="item.quantity" required>
              <div class="invalid-feedback">
                Please provide a quantity.
              </div>
            </div>
            <div class="col-md-3">
              <label for="price" class="form-label">Price*</label>
              <input type="number" class="form-control" v-model="item.price" required>
              <div class="invalid-feedback">
                Please provide a price.
              </div>
            </div>
            <div class="col-md-3">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" class="form-control" :value="item.quantity * item.price" readonly>
            </div>
          </div>
        </div>
      </div>

      <button type="button" class="btn btn-secondary mb-3" @click="addItem">Add Item</button>
      <div class="mb-3">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-danger">Cancel</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      suppliers: [], // Populate this with suppliers data
      allItems: [], // Populate this with all items data
      supplier: '',
      date: '',
      receiptNumber: '',
      description: '',
      items: [{ name: '', needByDate: '', unit: '', quantity: '', price: '' }]
    };
  },
  methods: {
    addItem() {
      this.items.push({ name: '', needByDate: '', unit: '', quantity: '', price: '' });
    },
    submitForm() {
      // Submit the form data
      console.log({
        supplier: this.supplier,
        date: this.date,
        receiptNumber: this.receiptNumber,
        description: this.description,
        items: this.items
      });
    }
  }
};
</script>

<style scoped>
/* Add any specific styles here */
</style>