import { createWebHistory, createRouter } from 'vue-router'

// const Dashboard = () => import('../components/Dashboard.vue');
// const Login = () => import('@/components/Login.vue');
// const Register = () => import('../components/Register.vue');
// const PasswordReset = () => import('../components/ForgotPassword.vue');

const routes = [
    {
        name: 'dashboard',
        path: "/",
        component: () => import('../components/Dashboard.vue'),
        // component: Dashboard
        children: [
            {
                name: 'home',
                path: '',
                component: () => import('../components/Home.vue'),
            },
            {
                name: "login",
                path: "login",
                component: () => import('../components/Auth/Login.vue'),
                meta: {
                    guest: true,
                    // middleware: "guest",
                    // title: `Login`
                }
            },
            {
                name: "register",
                path: "register",
                component: () => import('../components/Auth/Register.vue'),
                meta: {
                    guest: true,
                    // middleware: "guest",
                    // title: `Register`
                }
            },
            {
                name: "forgot-password",
                path: "forgot-password",
                component: () => import('../components/Auth/ForgotPassword.vue'),
                meta: {
                    guest: true,
                    // middleware: "guest",
                    // title: `PasswordReset`
                }
            },
            {
                path: '/verify-email/:id/:hash',
                props: route => ({
                    id: route.params.id,
                    hash: route.params.hash
                }),
                component: () => import('../components/Auth/VerifyEmail.vue'),
                name: 'verify-email',

            },
            {
                path: '/reset-password/:token',
                props: route => ({
                    token: route.params.token,
                    email: route.query.email
                }),
                component: () => import('../components/Auth/ResetPassword.vue'),
                name: 'reset-password',
                meta : {
                    guest : true
                }
            },
            {
                path: 'admin',
                component: () => import('../components/Admin/Admin.vue'),
                name: 'admin_page',
                meta : {
                    // guest : true
                },
                children: [
                    {
                        path: 'users',
                        component: () => import('../components/Admin/Users/Users.vue'),
                        name: 'users',
                    },

                    {
                        path: 'roles',
                        component: () => import('../components/Admin/Roles/Roles.vue'),
                        name: 'roles',
                    },{
                        path: 'permissions',
                        component: () => import('../components/Admin/Permissions.vue'),
                        name: 'permissions',
                    },
                    {
                        path: 'hospitals',
                        component: () => import('../components/Admin/Hospitals.vue'),
                        name: 'hospitals',
                    },
                    {
                        path: 'diseases',
                        component: () => import('../components/Admin/Diseases.vue'),
                        name: 'diseases',
                    }
                ]
            },
            {
                path: '/admin/users/:id/edit',
                props: route => ({
                    id: route.params.id,
                }),
                component: () => import('../components/Admin/Users/Edit.vue'),
                name: 'user_edit',
            },
            {
                path: '/admin/users/:id/show',
                props: route => ({
                    id: route.params.id,
                }),
                component: () => import('../components/Admin/Users/Show.vue'),
                name: 'user_show',
            },
            {
                path: '/admin/roles/create',
                component: () => import('../components/Admin/Roles/Create.vue'),
                name: 'role_create',
            },
            {
                path: '/admin/roles/:id/edit',
                props: route => ({
                    id: route.params.id,
                }),
                component: () => import('../components/Admin/Roles/Edit.vue'),
                name: 'role_edit',
            },
            {
                path: '/admin/roles/:id/show',
                props: route => ({
                    id: route.params.id,
                }),
                component: () => import('../components/Admin/Roles/Show.vue'),
                name: 'role_show',
            },
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







router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('access_token') == null) {
            next({
                // path: '/login',
                name: 'login',
                params: { nextUrl: to.fullPath }
            })
        } else {
            let user = JSON.parse(localStorage.getItem('user'))
            if(to.matched.some(record => record.meta.is_admin)) {
                if(user.is_admin == 1){
                    next()
                }
                else{
                    next({ name: 'userboard'})
                }
            }else {
                next()
            }
        }
    } else if(to.matched.some(record => record.meta.guest)) {
        if(localStorage.getItem('access_token') == null){
            next()
        }
        else{
            next({ name: 'home'})
        }
    }else {
        next()
    }
})





export default router
