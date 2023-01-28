import { createWebHistory, createRouter } from 'vue-router'
import {isNull} from "lodash/lang";

// const Dashboard = () => import('../components/Dashboard.vue');
// const Login = () => import('@/components/Login.vue');
// const Register = () => import('../components/Register.vue');
// const PasswordReset = () => import('../components/ForgotPassword.vue');

const routes = [
    {
        name: 'dashboard',
        path: "/",
        component: () => import('../components/Dashboard.vue'),
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
                    email_verified: true,
                    requiresAuth: true,
                },
                children: [
                    {
                        path: 'users',
                        component:() => import('../components/Admin/Users/Users.vue'),
                        name: 'users',
                        children: [
                            {
                                path: ':id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Users/Edit.vue'),
                                name: 'user_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: ':id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Users/Show.vue'),
                                name: 'user_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                        ]
                    },
                    {
                        path: 'roles',
                        component: () => import('../components/Admin/Roles/Roles.vue'),
                        name: 'roles',
                        children: [
                            {
                                path: '/admin/roles/create',
                                component: () => import('../components/Admin/Roles/Create.vue'),
                                name: 'role_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/roles/:id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Roles/Edit.vue'),
                                name: 'role_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/roles/:id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Roles/Show.vue'),
                                name: 'role_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                        ]
                    },{
                        path: 'permissions',
                        component: () => import('../components/Admin/Permissions/Permissions.vue'),
                        name: 'permissions',
                        children: [
                            {
                                path: '/admin/permissions/create',
                                component: () => import('../components/Admin/Permissions/Create.vue'),
                                name: 'permission_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/permissions/:id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Permissions/Edit.vue'),
                                name: 'permission_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/permissions/:id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Permissions/Show.vue'),
                                name: 'permission_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                        ]
                    },
                    {
                        path: 'hospitals',
                        component: () => import('../components/Admin/Hospitals/Hospitals.vue'),
                        name: 'hospitals',
                        children: [
                            {
                                path: '/admin/hospitals/create',
                                component: () => import('../components/Admin/Hospitals/Create.vue'),
                                name: 'hospital_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/hospitals/:id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Hospitals/Edit.vue'),
                                name: 'hospital_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/hospitals/:id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Hospitals/Show.vue'),
                                name: 'hospital_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                        ]
                    },
                    {
                        path: 'diseases',
                        component: () => import('../components/Admin/Diseases/Diseases.vue'),
                        name: 'diseases',
                        children: [
                            {
                                path: '/admin/diseases/create',
                                component: () => import('../components/Admin/Diseases/Create.vue'),
                                name: 'disease_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/diseases/:id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Diseases/Edit.vue'),
                                name: 'disease_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                            {
                                path: '/admin/diseases/:id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Diseases/Show.vue'),
                                name: 'disease_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                },
                            },
                        ]
                    }
                ]
            },











            {
                path: '/hospitals/:id/booking',
                props: route => ({
                    id: route.params.id,
                }),
                component: () => import('../components/HospitalBooking.vue'),
                name: 'hospital_booking',
                meta : {
                    // email_verified: true,
                    // requiresAuth: true,
                },
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
            let user = JSON.parse(localStorage.getItem('auth_user'))
            if(to.matched.some(record => record.meta.is_admin)) {
                if(user.is_admin == 1){
                    next()
                }
                else{
                    next({ name: 'userboard'})
                }
            }else if (to.matched.some(record => record.meta.email_verified)) {
                if (isNull(user.email_verified_at)) {
                    next({ name: 'home'})
                } else next();
            } else {
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
