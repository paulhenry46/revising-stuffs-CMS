// 1. Importation des scripts Firebase (version compat pour plus de stabilité en PWA)
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

// 2. Initialisation (Remplace avec tes vraies clés Firebase Console)
firebase.initializeApp({
  apiKey: "{{ env('FCM_apiKey') }}",
  authDomain: "{{ env('FCM_authDomain') }}",
  projectId: "{{ env('FCM_projectId') }}",
  storageBucket: "{{ env('FCM_storageBucket') }}",
  messagingSenderId: "{{ env('FCM_messagingSenderId') }}",
  appId: "{{ env('FCM_appId') }}"
});

const messaging = firebase.messaging();

// 3. Gestion des messages en arrière-plan (Background)
// C'est cette fonction qui réveille le navigateur même fermé
messaging.onBackgroundMessage((payload) => {
  console.log('[sw.js] Message reçu en arrière-plan:', payload);

  const title = payload.notification?.title || payload.data?.title || "Nouvelle ressource !";
  const notificationOptions = {
    body: payload.notification?.body || payload.data?.body || "",
    icon: payload.notification?.image || payload.data?.icon || "/icon.png",
    // On stocke l'URL dans les data pour la récupérer au clic
    data: {
      url: payload.data?.link || "/news"
    },
  };

  return self.registration.showNotification(title, notificationOptions);
});

// 4. Gestion du clic sur la notification
self.addEventListener("notificationclick", (event) => {
  event.notification.close();

  // On récupère l'URL stockée plus haut
  const url = event.notification?.data?.url || "/news";

  event.waitUntil(
    clients.matchAll({ type: "window", includeUncontrolled: true }).then((clientList) => {
      // Si un onglet avec cette URL est déjà ouvert, on met le focus dessus
      for (const client of clientList) {
        if (client.url === url && "focus" in client) return client.focus();
      }
      // Sinon on ouvre une nouvelle fenêtre
      if (clients.openWindow) return clients.openWindow(url);
    })
  );
});