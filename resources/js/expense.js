import { createApp } from 'vue';
import ExpenseCategory from './components/expense/ExpenseCategory.vue';
import Expense from './components/expense/Expense.vue';
import VueAwesomePaginate from "vue-awesome-paginate";

// import the necessary css file

import "vue-awesome-paginate/dist/style.css";
const app = createApp()
app.component('expense-category', ExpenseCategory)
app.component('expense', Expense)
app.use(VueAwesomePaginate);
app.mount('#app');