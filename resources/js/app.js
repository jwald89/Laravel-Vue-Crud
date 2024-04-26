import './bootstrap';
import router from './router'
import Dashboard from './Dashboard.vue'
import Create from './components/personal-details/Create.vue'
import List from './components/personal-details/List.vue'
import Edit from './components/personal-details/Edit.vue'
import Transactions from './modules/Transactions.vue'
import { createApp } from 'vue';

createApp({
    component: {
        Dashboard,
        Create,
        List,
        Edit,
        Transactions
    }
}).use(router).mount('#app')
