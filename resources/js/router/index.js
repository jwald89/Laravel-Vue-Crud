import {
    createRouter,
    createWebHistory
} from 'vue-router'
import Dashboard from '../Dashboard.vue'
import Create from '../components/personal-details/Create.vue'
import List from '../components/personal-details/List.vue'
import Edit from '../components/personal-details/Edit.vue'
import Transactions from '../modules/Transactions.vue'

const routes = [{
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/create-personal-details',
        name: 'create.personal.details',
        component: Create
    },
    {
        path: '/personal-details',
        name: 'personal.details',
        component: List
    },
    {
        path: '/edit-personal-details/:id/:page?',
        name: 'personal.details.edit',
        component: Edit
    },
    {
        path: '/transactions',
        name: 'transactions',
        component: Transactions
    }
]

const router = createRouter({
    'history': createWebHistory(),
    routes
})

export default router
