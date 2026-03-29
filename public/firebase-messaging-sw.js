self.addEventListener("push", (event) => {
  let payload = {};
  try {
    payload = event.data ? event.data.json() : {};
  } catch (e) {
    payload = {};
  }

  // FCM commonly wraps payload in different shapes.
  // We primarily rely on data.link that the backend sets.
  const notification = payload.notification || {};
  const data = payload.data || {};

  const title = notification.title || data.title || "Notification";
  const body = notification.body || data.body || "";
  const icon = notification.image || data.icon;

  const url =
    data.link ||
    (payload.webpush && payload.webpush.fcm_options && payload.webpush.fcm_options.link) ||
    notification.click_action ||
    "/news";

  event.waitUntil(
    self.registration.showNotification(title, {
      body,
      icon,
      data: { url },
    })
  );
});

self.addEventListener("notificationclick", (event) => {
  event.notification.close();

  const url = event.notification?.data?.url || "/news";

  event.waitUntil(
    clients.matchAll({ type: "window", includeUncontrolled: true }).then((clientList) => {
      // Focus if already open
      for (const client of clientList) {
        if (client.url === url && "focus" in client) return client.focus();
      }
      return clients.openWindow(url);
    })
  );
});
