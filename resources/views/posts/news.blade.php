<x-app-layout>
    <x-slot name="header">
        @php
     $breadcrumb = array (
  array(__('New posts'),NULL)
);

     @endphp   
     <x-breadcrumb :items=$breadcrumb/>  
    </x-slot>
    <div class="py-12">
        @livewire('new-posts')
        </div>
    </div>
<x-nav-bottom :active=0/>
@auth
@if(env('FirebasePush') == true)
<script type = "module" >
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js";
import {
    getMessaging,
    getToken
} from "https://www.gstatic.com/firebasejs/10.8.1/firebase-messaging.js";


const firebaseConfig = {
    apiKey: "AIzaSyAMnBdGZEJ9hDUDnbHySypHrj6A792p00o",
    authDomain: "blog-paulhenry-eu.firebaseapp.com",
    projectId: "blog-paulhenry-eu",
    storageBucket: "blog-paulhenry-eu.appspot.com",
    messagingSenderId: "631699317087",
    appId: "1:631699317087:web:26586c0d0e276eadcf56ea"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

navigator.serviceWorker.register("firebase-messaging-sw.js").then(registration => {
    getToken(messaging, {
        serviceWorkerRegistration: registration,
        vapidKey: 'BPSAojUccMHZujJZpLF-W2CzKZG7xw5aqSjrE97V2SPIuLPDCnjbPvx831KHRhT2Z8WzqjQvFMSIaUiZpMg_KRQ'
    }).then((currentToken) => {
        if (currentToken) {
            console.log("Token is: " + currentToken);

            axios.post("/fcm-token", {
                _method: "PATCH",
                currentToken
            })
            // Send the token to your server and update the UI if necessary
            // ...
        } else {
            // Show permission request UI
            console.log('No registration token available. Request permission to generate one.');
            Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    console.log('Notification permission granted.');

                }
            })
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        // ...
    });
});
</script>
@endif
@endauth
</x-app-layout>
