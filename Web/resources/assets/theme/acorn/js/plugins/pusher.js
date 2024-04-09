// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher(PUSHER_APP_KEY, {
  cluster: PUSHER_APP_CLUSTER,
  encrypted: true
});