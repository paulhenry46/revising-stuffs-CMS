//import {  } from 'https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js';
//import {  } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-messaging-sw.js";

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
const firebaseApp = initializeApp({
  databaseURL: 'https://blog-paulhenry-eu.firebaseio.com',
  apiKey: "AIzaSyAMnBdGZEJ9hDUDnbHySypHrj6A792p00o",
    authDomain: "blog-paulhenry-eu.firebaseapp.com",
    projectId: "blog-paulhenry-eu",
    storageBucket: "blog-paulhenry-eu.appspot.com",
    messagingSenderId: "631699317087",
    appId: "1:631699317087:web:26586c0d0e276eadcf56ea"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = getMessaging(firebaseApp);

// Handle incoming messages. Called when:
// - a message is received while the app has focus
// - the user clicks on an app notification created by a service worker
//   `messaging.onBackgroundMessage` handler.
import { onMessage } from "https://www.gstatic.com/firebasejs/10.8.1/firebase-messaging.js";

onMessage(messaging, (payload) => {
  console.log('Message received. ', payload);
  // ...
});

onBackgroundMessage(messaging, (payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'Background Message Title';
    const notificationOptions = {
      body: 'Background Message body.',
      icon: '/pwa/icon48.png'
    };

    self.registration.showNotification(notificationTitle,
      notificationOptions);
  });
