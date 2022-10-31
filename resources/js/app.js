require("./bootstrap");

var channel = Echo.channel("notification");
channel.listen("MessageNotification", () => {
    Swal.fire({
        title: "<h1>New order!</h1>",
        html: "<h5>New order has been added to the queue!</h5>",
        icon: "info",
    }).then(() => window.location.reload());
});
