<template>
  
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