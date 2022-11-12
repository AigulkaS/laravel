import { createWebHistory, createRouter } from 'vue-router'
// import store from '@/store'
/* Guest Component */
// const Login = () => import('../components/Login.vue')
// const Register = () => import('@/components/Register.vue')
/* Guest Component */
/* Layouts */
// const DahboardLayout = () => import('@/components/layouts/Default.vue')
// const DahboardLayout = () => import('../components/Dashboard.vue')
/* Layouts */
/* Authenticated Component */
// const Dashboard = () => import('@/components/Dashboard.vue')
/* Authenticated Component */


const Dashboard = () => import('../components/Dashboard.vue');
const Login = () => import('@/components/Login.vue');
const Register = () => import('../components/Register.vue');

const routes = [
    {
        name: 'dashboard',
        path: "/",
        component: Dashboard,
        children: [
            {
                name: "login",
                path: "login",
                component: Login,
                meta: {
                    middleware: "guest",
                    title: `Login`
                }
            },
            {
                name: "register",
                path: "register",
                component: Register,
                meta: {
                    middleware: "guest",
                    title: `Register`
                }
            }
        ]
    }
    // {
    //     name: "login",
    //     path: "/login",
    //     component: Login,
    //     meta: {
    //         middleware: "guest",
    //         title: `Login`
    //     }
    // },
    // {
    //     name: "register",
    //     path: "/register",
    //     component: Register,
    //     meta: {
    //         middleware: "guest",
    //         title: `Register`
    //     }
    // },
    // {
    //     path: "/",
    //     component: DahboardLayout,
    //     meta: {
    //         middleware: "auth"
        // },
        // children: [
            // {
            //     name: "dashboard",
            //     path: '/',
            //     component: Dashboard,
            //     meta: {
            //         title: `Dashboard`
            //     }
            // }
        // ]
    // }
]
const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})
// router.beforeEach((to, from, next) => {
//     document.title = to.meta.title
//     if (to.meta.middleware == "guest") {
//         if (store.state.auth.authenticated) {
//             next({ name: "dashboard" })
//         }
//         next()
//     } else {
//         if (store.state.auth.authenticated) {
//             next()
//         } else {
//             next({ name: "login" })
//         }
//     }
// })
export default router
