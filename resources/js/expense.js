import { createApp } from 'vue';
import ExpenseCategory from './components/expense/ExpenseCategory.vue';
import Expense from './components/expense/Expense.vue';
import './bootstrap';
const app = createApp()
app.component('expense-category', ExpenseCategory)
app.component('expense', Expense)
app.mount('#app');