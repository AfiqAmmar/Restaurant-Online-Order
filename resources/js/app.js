require("./bootstrap");

var channel = Echo.channel("notification");
var soundfile = "/audio/mixkit-doorbell-single-press-333.wav";

channel.listen("MessageNotification", () => {
    Swal.fire({
        title: "<h1>New order!</h1>",
        html: "<h5>New order has been added to the queue!</h5>",
        icon: "info",
        didOpen: () => {
            var audio = new Audio(soundfile);
            audio.play();
        },
    }).then(() => window.location.reload());
});
