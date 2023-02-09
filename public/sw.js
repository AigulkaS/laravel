console.log(22222)

// self.addEventListener("push", e => {
//     const data = e.data.json();
//     self.registration.showNotification(
//         data.title, // title of the notification
//         {
//             body: "Push notification from section.io", //the body of the push notification
//             image: "https://pixabay.com/vectors/bell-notification-communication-1096280/",
//             icon: "https://pixabay.com/vectors/bell-notification-communication-1096280/" // icon
//         }
//     );
// });

// "use strict";
//
// self.addEventListener("install", function(event) {
//     console.log(event)
//     // self.skipWaiting();
// });
//
// self.addEventListener("activate", function(event) {
//     console.log(event)
//
//     // event.waitUntil(self.clients.claim());
// });
//
// self.addEventListener("push", function(event) {
//     console.log(event)
//
//     if (!(self.Notification && self.Notification.permission === 'granted')) {
//         return;
//     }
//
//     const payload = event.data ? event.data.json() : {};
//     event.waitUntil(self.registration.showNotification(payload.title, payload));
// });
//
// self.addEventListener('notificationclick', function (event) {
//         console.log(event);
//         var noti = event.notification.data;
//         let r = event.notification.data.json();
//         console.log(noti); },
//     false); //handling the click
//
//




self.addEventListener('push', function (e) {
    console.log(333333)
    console.log(e)


    if (!(self.Notification && self.Notification.permission === 'granted')) {
        //notifications aren't supported or permission not granted!
        return;
    }

    if (e.data) {
        var msg = e.data;
        // var msg = e.data.json();
        console.log(msg)
        e.waitUntil(self.registration.showNotification(msg, {
            // body: msg.body,
            body: msg,
            // icon: msg.icon,
            // actions: msg.actions
        }));
    }
});

        // 'use strict'
        //
        // const WebPush = {
        //     init () {
        //         console.log(888888)
        //         self.addEventListener('push', this.notificationPush.bind(this))
        //         self.addEventListener('notificationclick', this.notificationClick.bind(this))
        //         self.addEventListener('notificationclose', this.notificationClose.bind(this))
        //
        //
        //
        //         self.addEventListener("install", function(event) {
        //             console.log(event)
        //             // self.skipWaiting();
        //         });
        //
        //         self.addEventListener("activate", function(event) {
        //             console.log(event)
        //             // event.waitUntil(self.clients.claim());
        //         });
        //
        //         // self.addEventListener("push", function(event) {
        //         //     if (!(self.Notification && self.Notification.permission === 'granted')) {
        //         //         return;
        //         //     }
        //         //
        //         //     const payload = event.data ? event.data.json() : {};
        //         //     event.waitUntil(self.registration.showNotification(payload.title, payload));
        //         // });
        //
        //
        //
        //     },
        //
        //     /**
        //      * Handle notification push event.
        //      *
        //      * https://developer.mozilla.org/en-US/docs/Web/Events/push
        //      *
        //      * @param {NotificationEvent} event
        //      */
        //     notificationPush (event) {
        //         if (!(self.Notification && self.Notification.permission === 'granted')) {
        //             return
        //         }
        //
        //         // https://developer.mozilla.org/en-US/docs/Web/API/PushMessageData
        //         if (event.data) {
        //             event.waitUntil(
        //                 this.sendNotification(event.data.json())
        //             )
        //         }
        //     },
        //
        //     /**
        //      * Handle notification click event.
        //      *
        //      * https://developer.mozilla.org/en-US/docs/Web/Events/notificationclick
        //      *
        //      * @param {NotificationEvent} event
        //      */
        //     notificationClick (event) {
        //         // console.log(event.notification)
        //
        //         if (event.action === 'some_action') {
        //             // Do something...
        //         } else {
        //             self.clients.openWindow('/')
        //         }
        //     },
        //
        //     /**
        //      * Handle notification close event (Chrome 50+, Firefox 55+).
        //      *
        //      * https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/onnotificationclose
        //      *
        //      * @param {NotificationEvent} event
        //      */
        //     notificationClose (event) {
        //         self.registration.pushManager.getSubscription().then(subscription => {
        //             if (subscription) {
        //                 this.dismissNotification(event, subscription)
        //             }
        //         })
        //     },
        //
        //     /**
        //      * Send notification to the user.
        //      *
        //      * https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerRegistration/showNotification
        //      *
        //      * @param {PushMessageData|Object} data
        //      */
        //     sendNotification (data) {
        //         return self.registration.showNotification(data.title, data)
        //     },
        //
        //     /**
        //      * Send request to server to dismiss a notification.
        //      *
        //      * @param  {NotificationEvent} event
        //      * @param  {String} subscription.endpoint
        //      * @return {Response}
        //      */
        //     dismissNotification ({ notification }, { endpoint }) {
        //         if (!notification.data || !notification.data.id) {
        //             return
        //         }
        //
        //         const data = new FormData()
        //         data.append('endpoint', endpoint)
        //
        //         // Send a request to the server to mark the notification as read.
        //         fetch(`/notifications/${notification.data.id}/dismiss`, {
        //             method: 'POST',
        //             body: data
        //         })
        //     }
        // }
        //
        //
        // console.log(111111);
        // WebPush.init()
