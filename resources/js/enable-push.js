console.log('enable-push.js');

// if (!localStorage.getItem('access_token')) {
    initSW();
// }

function initSW() {
    if (!"serviceWorker" in navigator) {
        //service worker isn't supported
        return;
    }

    //don't use it here if you use service worker
    //for other stuff.
    if (!"PushManager" in window) {
        //push isn't supported
        return;
    }
    //register the service worker
    // console.log(navigator)
    // console.log(navigator.serviceWorker)

    if ('serviceWorker' in navigator) {

        navigator.serviceWorker.register('/sw.js')
            .then(() => {
                console.log(88888888888)
                console.log('serviceWorker installed!')
                initPush();
            })
            .catch((err) => {
                console.log(11111)
                console.log(err)
            });
    }
}

function initPush() {
    if (!navigator.serviceWorker.ready) {
        return;
    }

    new Promise(function (resolve, reject) {
        const permissionResult = Notification.requestPermission(function (result) {
            resolve(result);
        });

        if (permissionResult) {
            permissionResult.then(resolve, reject);
        }
    })
        .then((permissionResult) => {
            if (permissionResult !== 'granted') {
                throw new Error('We weren\'t granted permission.');
            }
            subscribeUser();
        });
}

function subscribeUser() {
    navigator.serviceWorker.ready
        .then((registration) => {
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    import.meta.env.VITE_APP_VAPID_PUBLIC_KEY
                )
            };

            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then((pushSubscription) => {
            console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
            storePushSubscription(pushSubscription);
        });
}

function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function storePushSubscription(pushSubscription) {

    const key = pushSubscription.getKey('p256dh')
    const token = pushSubscription.getKey('auth')
    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
    const data = {
        endpoint: pushSubscription.endpoint,
        publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
        authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
        contentEncoding
    }
    // axios.post('/subscriptions', data)
    //     .then(() => { this.loading = false })
    if (localStorage.getItem('auth_user')) {
        axios.post('/api/push', data, {
            headers: {
                Authorization: localStorage.getItem('access_token')
            }
        }).then(res => {
            console.log(res)
            // this.loading = false
        }).catch(err => {
            console.log(err)
            console.log(err.response)
        })
    }




    // const token = localStorage.getItem('access_token');
    // console.log(token);
    console.log(pushSubscription);

    // const key = pushSubscription.getKey('p256dh');
    // console.log(4444);
    // console.log(key);
    //
    // const token = pushSubscription.getKey('auth')
    // console.log(4444);
    // console.log(token);
    // const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
    // console.log(4444);
    // console.log(contentEncoding);
    //
    // const data = {
    //     endpoint: pushSubscription.endpoint,
    //     public_key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
    //     auth_token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
    //     encoding: contentEncoding,
    // };

    // axios.post('/api/push', data, {
    // axios.post('/api/push', pushSubscription, {
    //     headers: {
    //         Authorization: localStorage.getItem('access_token')
    //     }
    // }).then(res => {
    //     console.log(res)
    // }).catch(err => {
    //     console.log(err)
    //     console.log(err.response)
    // })


    // axios.get('/api/push', {
    //     headers: {Authorization: localStorage.getItem('access_token')}
    // }).then(res => {
    //     console.log(res)
    // }).catch(err => {
    //     console.log(err)
    //     console.log(err.response)
    // })

    // fetch('/push', {
    //     method: 'POST',
    //     body: JSON.stringify(pushSubscription),
    //     headers: {
    //         'Accept': 'application/json',
    //         'Content-Type': 'application/json',
    //         'X-CSRF-Token': token
    //     }
    // })
    //     .then((res) => {
    //         return res.json();
    //     })
    //     .then((res) => {
    //         console.log(res)
    //     })
    //     .catch((err) => {
    //         console.log(err)
    //     });
}
