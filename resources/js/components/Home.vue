<template>
    <div>
<!--&lt;!&ndash;         Send notification button&ndash;&gt;-->
<!--        <button-->
<!--            :disabled="loading"-->
<!--            type="button"-->
<!--            class="btn btn-success btn-send" @click="sendNotification"-->
<!--        >-->
<!--            Send Notification-->
<!--        </button>-->

<!--        &lt;!&ndash; Enable/Disable push notifications &ndash;&gt;-->
<!--        <button-->
<!--            :disabled="pushButtonDisabled || loading"-->
<!--            type="button"-->
<!--            class="btn btn-primary"-->
<!--            :class="{ 'btn-primary': !isPushEnabled, 'btn-danger': isPushEnabled }"-->
<!--            @click="togglePush"-->
<!--        >-->
<!--            {{ isPushEnabled ? 'Disable' : 'Enable' }} Push Notifications-->
<!--        </button>-->


<!--        <button type="button" class="btn btn-primary" @click.prevent="pushDemo()">-->
<!--            PushDemo-->
<!--        </button>-->

        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">

                    <div v-if="auth_user">
                        <div v-if="!auth_user.email_verified_at" class="alert alert-success">
                            На ваш почтовый адрес было направлено письмо для подтверждения Email.
                            <div class="fw-bold">
                                Только после подтверждения Email будут доступны все функции.
                            </div>
                            Для продолжения подвердите свою почту.
                            <div v-if="success" class="fw-bold">
                                {{success}}
                            </div>
                            <div class="fw-bolder">
                                <span>Если не поучили письмо - </span>
                                <button type="submit" :disabled="processing" class="btn btn-link"
                                        @click.prevent="resend()">
                                    {{ processing ? wait : "отправить повторно письмо для подтверждения email" }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div v-if="auth_user && auth_user.email_verified_at
                            && [roles.cardiologist, roles.surgeon].includes(auth_user.role_name)">
                            <HospitalBooking></HospitalBooking>
                        </div>
                        <div v-else>
                            <HospitalsCard></HospitalsCard>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
import HospitalsCard from "./HospitalsCard.vue";
import HospitalBooking from "./HospitalBooking.vue";
import {roles, wait} from "../consts";

export default {
    name: "Home",
    components: {
        HospitalsCard,
        HospitalBooking
    },
    data() {
      return {
          success: null,
          processing:false,
          roles,
          wait,

          loading: false,
          isPushEnabled: false,
          pushButtonDisabled: true
      }
    },
    mounted() {
        this.successPage = true;


        // this.registerServiceWorker()

    },
    computed: {
        auth_user() {
            console.log(localStorage)
            return localStorage.getItem('auth_user')
                ? JSON.parse(localStorage.getItem('auth_user')) : null
        }
    },
    methods: {
        pushDemo() {
            console.log('push.demo')
            axios.get('/api/push', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res)
            }).catch(err => {
                console.log(err)
                console.log(err.response)
            })
        },

        /**
         * Register the service worker.
         */
        registerServiceWorker () {

            if ("serviceWorker" in navigator) {
                navigator.serviceWorker
                    .register("/sw.js")
                    .then((registration) => {
                        // registration worked
                        console.log("Registration succeeded.");
                        registration.unregister().then((boolean) => {
                            console.log(boolean)
                            // if boolean = true, unregister is successful
                        });
                    })
                    .catch((error) => {
                        // registration failed
                        console.error(`Registration failed with ${error}`);
                    });
            }

            // if (!('serviceWorker' in navigator)) {
            //     console.log('Service workers aren\'t supported in this browser.')
            //     return
            // }
            // navigator.serviceWorker.register('/sw.js')
            //     .then((e) => {
            //         console.log(e)
            //         this.initialiseServiceWorker()
            //     }).catch(err => {
            //         console.log(err)
            //         console.log(err.response)
            // })
        },
        initialiseServiceWorker () {
            if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
                console.log('Notifications aren\'t supported.')
                return
            }
            if (Notification.permission === 'denied') {
                console.log('The user has blocked notifications.')
                return
            }
            if (!('PushManager' in window)) {
                console.log('Push messaging isn\'t supported.')
                return
            }
            navigator.serviceWorker.ready.then(registration => {
                console.log(registration);
                registration.pushManager.getSubscription()
                    .then(subscription => {
                        this.pushButtonDisabled = false
                        if (!subscription) {
                            return
                        }
                        this.updateSubscription(subscription)
                        this.isPushEnabled = true
                    })
                    .catch(e => {
                        console.log('Error during getSubscription()', e)
                    })
            })
        },
        /**
         * Subscribe for push notifications.
         */
        subscribe () {
            navigator.serviceWorker.ready.then(registration => {
                const options = { userVisibleOnly: true }
                // const vapidPublicKey = window.Laravel.vapidPublicKey
                const vapidPublicKey = import.meta.env.VITE_APP_VAPID_PUBLIC_KEY
                if (vapidPublicKey) {
                    options.applicationServerKey = this.urlBase64ToUint8Array(vapidPublicKey)
                }
                registration.pushManager.subscribe(options)
                    .then(subscription => {
                        this.isPushEnabled = true
                        this.pushButtonDisabled = false
                        this.updateSubscription(subscription)
                    })
                    .catch(e => {
                        if (Notification.permission === 'denied') {
                            console.log('Permission for Notifications was denied')
                            this.pushButtonDisabled = true
                        } else {
                            console.log('Unable to subscribe to push.', e)
                            this.pushButtonDisabled = false
                        }
                    })
            })
        },
        /**
         * Unsubscribe from push notifications.
         */
        unsubscribe () {
            navigator.serviceWorker.ready.then(registration => {
                registration.pushManager.getSubscription().then(subscription => {
                    if (!subscription) {
                        this.isPushEnabled = false
                        this.pushButtonDisabled = false
                        return
                    }
                    subscription.unsubscribe().then(() => {
                        this.deleteSubscription(subscription)
                        this.isPushEnabled = false
                        this.pushButtonDisabled = false
                    }).catch(e => {
                        console.log('Unsubscription error: ', e)
                        this.pushButtonDisabled = false
                    })
                }).catch(e => {
                    console.log('Error thrown while unsubscribing.', e)
                })
            })
        },
        /**
         * Toggle push notifications subscription.
         */
        togglePush () {
            if (this.isPushEnabled) {
                this.unsubscribe()
            } else {
                this.subscribe()
            }
        },
        /**
         * Send a request to the server to update user's subscription.
         *
         * @param {PushSubscription} subscription
         */
        updateSubscription (subscription) {
            const key = subscription.getKey('p256dh')
            const token = subscription.getKey('auth')
            const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
            const data = {
                endpoint: subscription.endpoint,
                publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                contentEncoding
            }
            this.loading = true
            // axios.post('/subscriptions', data)
            //     .then(() => { this.loading = false })

            axios.post('/api/push', data, {
                headers: {
                    Authorization: localStorage.getItem('access_token')
                }
            }).then(res => {
                console.log(res)
                this.loading = false
            }).catch(err => {
                console.log(err)
                console.log(err.response)
            })
        },
        /**
         * Send a requst to the server to delete user's subscription.
         *
         * @param {PushSubscription} subscription
         */
        deleteSubscription (subscription) {
            this.loading = true
            axios.post('/subscriptions/delete', { endpoint: subscription.endpoint })
                .then(() => { this.loading = false })
        },
        /**
         * Send a request to the server for a push notification.
         */
        sendNotification () {
            this.loading = true
            axios.post('/api/notifications', {}, {
                headers: {
                    Authorization: localStorage.getItem('access_token')
                }
            }).then((res) => {
                console.log(res)
                this.loading = false
            }).catch(err => {
                console.log(err)
                console.log(err.response)
            })

        },
        /**
         * https://github.com/Minishlink/physbook/blob/02a0d5d7ca0d5d2cc6d308a3a9b81244c63b3f14/app/Resources/public/js/app.js#L177
         *
         * @param  {String} base64String
         * @return {Uint8Array}
         */
        urlBase64ToUint8Array (base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4)
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/')
            const rawData = window.atob(base64)
            const outputArray = new Uint8Array(rawData.length)
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i)
            }
            return outputArray
        },



        getData() {
            axios.get('/api/get', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res)
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        resend(){
            this.errors = null;
            this.success = null;
            this.processing = true
            axios.post('/api/verify-resend', {id: this.auth_user.id})
                .then((res) =>{
                    console.log(res)
                    this.success = res.data.message;
                    setTimeout(()=>{
                        this.success = null;
                    },5000)
                })
                .catch((err) =>{
                    this.errorsMessage(err);
                })
                .finally(() => {
                    this.processing = false
                });
        },

    }
}
</script>

