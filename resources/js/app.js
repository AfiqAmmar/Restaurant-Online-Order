require("./bootstrap");

var channel = Echo.channel("notification");
channel.listen("MessageNotification", (e) => {
    alert("New order has been added to the queue! Please refresh if you are already in the order queue page.");
});
