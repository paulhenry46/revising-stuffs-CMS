importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "{{env('FCM_apiKey')}}",
    projectId: "{{env('FCM_projectId')}}",
    messagingSenderId: "{{env('FCM_messagingSenderId')}}",
    appId: "{{env('FCM_appId')}}"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});