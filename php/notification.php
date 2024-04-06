<?php if(isset($_SESSION['notification'])): ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var message = "<?php echo addslashes($_SESSION['notification']); unset($_SESSION['notification']); ?>";
    showNotification(message);
});

function showNotification(message) {
    var notification = document.createElement("div");
    notification.textContent = message;
    notification.className = "notification";
    document.body.appendChild(notification);

    notification.offsetHeight; // Trigger reflow

    notification.classList.add("show");

    setTimeout(function() {
        notification.classList.remove("show");
        setTimeout(function() {
            notification.remove();
        }, 500); // Adjust this time to match your animation duration
    }, 5000);
}
</script>
<style>
.notification {
    background: #1e1e1e;
    border: 1px solid #222222;
    color: white;
    padding: 10px;
    position: fixed;
    bottom: -100px;
    left: 20px;
    border-radius: 5px;
    transition: bottom 0.5s ease;
}

.notification.show {
    bottom: 20px;
}
</style>
<?php endif; ?>
