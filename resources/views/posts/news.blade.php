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


const firebaseConfig = {//TODO Add Firebase config
    apiKey: "XXX",
    authDomain: "XXX.firebaseapp.com",
    projectId: "XXX",
    storageBucket: "XXX.appspot.com",
    messagingSenderId: "XXX",
    appId: "1:XXX:web:XXX"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

grantButton = document.querySelector('#grantButton');
registerButton = document.querySelector('#registerButton');

grantButton.addEventListener('click', requestNotiffactionPermission);
registerButton.addEventListener('click', registerSW);


function requestNotiffactionPermission(){
    Notification.requestPermission().then((permission) => {
                if (permission === 'granted') {
                    console.log('Notification permission granted.');
                    grantButton.classList.add('btn-success');
                    grantButton.classList.remove('btn-primary');
                    grantButton.classList.remove('btn-error');

                }else{
                    console.log('Notification permission denied.');
                    grantButton.classList.add('btn-error');
                    grantButton.classList.remove('btn-primary');
                }
            })
}

function registerSW(){

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

            registerButton.classList.add('btn-success');
            registerButton.classList.remove('btn-primary');
            registerButton.classList.remove('btn-error');
        document.getElementById("SWProcess").close();
        document.getElementById("SWSuccess").showModal();
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        registerButton.classList.add('btn-error');
        registerButton.classList.remove('btn-primary');
    });
});

}
</script>
@endif
@endauth
</x-app-layout>
