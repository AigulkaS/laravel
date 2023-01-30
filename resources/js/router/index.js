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
        redirect: { name: 'home' },
        meta: {
            breadcrumb: 'Главная'
        },
        children: [
            {
                name: 'home',
                path: '',
                component: () => import('../components/Home.vue'),

            },
            {
                name: "profile",
                path: "profile",
                component: () => import('../components/Profile.vue'),
                meta: {
                    requiresAuth: true,
                    breadcrumb: 'Профиль',
                }
            },
            {
                name: "login",
                path: "login",
                component: () => import('../components/Auth/Login.vue'),
                meta: {
                    guest: true,
                    breadcrumb: 'Авторизация',
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
                    breadcrumb: 'Регистрация',
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
                    breadcrumb: 'Сброс пароля',
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
                meta: {
                    // guest: true,
                    breadcrumb: 'Подтвеждение Email',
                    // middleware: "guest",
                    // title: `PasswordReset`
                }

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
                    guest : true,
                    breadcrumb: 'Сбросить пароль',

                }
            },
            {
                path: 'admin',
                component: () => import('../components/Admin/Admin.vue'),
                name: 'admin_page',
                redirect: { name: 'users' },
                meta : {
                    // guest : true
                    email_verified: true,
                    requiresAuth: true,
                    breadcrumb: 'Админка',
                },
                children: [
                    {
                        path: 'users',
                        component:() => import('../components/Admin/Users/Users.vue'),
                        name: 'users',
                        meta : {
                            breadcrumb: 'Пользователи',
                        },
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
                                    breadcrumb: 'Редактировать',
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
                                    breadcrumb: 'Просмотр',
                                },
                            },
                        ]
                    },
                    {
                        path: 'roles',
                        component: () => import('../components/Admin/Roles/Roles.vue'),
                        name: 'roles',
                        meta : {
                            breadcrumb: 'Роли',
                        },
                        children: [
                            {
                                path: 'create',
                                component: () => import('../components/Admin/Roles/Create.vue'),
                                name: 'role_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Добавить роль',
                                },
                            },
                            {
                                path: ':id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Roles/Edit.vue'),
                                name: 'role_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Редактирование',

                                },
                            },
                            {
                                path: ':id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Roles/Show.vue'),
                                name: 'role_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Просмотр',
                                },
                            },
                        ]
                    },{
                        path: 'permissions',
                        component: () => import('../components/Admin/Permissions/Permissions.vue'),
                        name: 'permissions',
                        meta : {
                            breadcrumb: 'Разрешения',
                        },
                        children: [
                            {
                                path: 'create',
                                component: () => import('../components/Admin/Permissions/Create.vue'),
                                name: 'permission_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Добавить разрешение',

                                },
                            },
                            {
                                path: ':id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Permissions/Edit.vue'),
                                name: 'permission_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Редактирование',
                                },
                            },
                            {
                                path: ':id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Permissions/Show.vue'),
                                name: 'permission_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Просмотр',
                                },
                            },
                        ]
                    },
                    {
                        path: 'hospitals',
                        component: () => import('../components/Admin/Hospitals/Hospitals.vue'),
                        name: 'hospitals',
                        meta : {
                            breadcrumb: 'Больницы',
                        },
                        children: [
                            {
                                path: 'create',
                                component: () => import('../components/Admin/Hospitals/Create.vue'),
                                name: 'hospital_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Добавить больницу',
                                },
                            },
                            {
                                path: ':id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Hospitals/Edit.vue'),
                                name: 'hospital_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Редактирование',
                                },
                            },
                            {
                                path: ':id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Hospitals/Show.vue'),
                                name: 'hospital_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Просмотр',
                                },
                            },
                        ]
                    },
                    {
                        path: 'diseases',
                        component: () => import('../components/Admin/Diseases/Diseases.vue'),
                        name: 'diseases',
                        meta : {
                            breadcrumb: 'Диагнозы',
                        },
                        children: [
                            {
                                path: 'create',
                                component: () => import('../components/Admin/Diseases/Create.vue'),
                                name: 'disease_create',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Добавить новый диагноз',

                                },
                            },
                            {
                                path: ':id/edit',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Diseases/Edit.vue'),
                                name: 'disease_edit',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Редактирование',
                                },
                            },
                            {
                                path: ':id/show',
                                props: route => ({
                                    id: route.params.id,
                                }),
                                component: () => import('../components/Admin/Diseases/Show.vue'),
                                name: 'disease_show',
                                meta : {
                                    email_verified: true,
                                    requiresAuth: true,
                                    breadcrumb: 'Просмотр',
                                },
                            },
                        ]
                    }
                ]
            },

            {
                path: '/hospitals',
                component: () => import('../components/HospitalsCard.vue'),
                name: 'hospitals_booking',
                meta : {
                    breadcrumb: 'Больницы',
                    // email_verified: true,
                    // requiresAuth: true,
                },
            },
            {
                path: '/hospitals/:id/booking',
                props: route => ({
                    id: route.params.id,
                }),
                component: () => import('../components/HospitalBooking.vue'),
                name: 'hospital_booking',
                meta : {
                    breadcrumb: 'График',
                    // email_verified: true,
                    // requiresAuth: true,
                },
            },

        ]
    },
    {
        path: '/:pathMatch(.*)*',
        component: () => import('../components/ErrorPage/ErrorPage.vue'),
        props: () => ({
            err: {status: 404}
        }),
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})







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
