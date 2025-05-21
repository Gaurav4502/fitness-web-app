document.addEventListener("DOMContentLoaded", function() {
    // Toggle menu
    const menuIcon = document.querySelector(".menu-icon");
    const navMenu = document.querySelector("#nav-menu");

    menuIcon.addEventListener("click", function() {
        navMenu.classList.toggle("show");
    });

    // Modal for profile picture
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");

    window.openModal = function(src) {
        modal.style.display = "flex";
        modalImg.src = src;
    };

    window.closeModal = function() {
        modal.style.display = "none";
    };

    // Mark messages as read when clicked
    document.querySelectorAll('.message-item').forEach(item => {
        item.addEventListener('click', () => {
            item.style.fontWeight = 'normal';
            item.style.backgroundColor = 'white';
            // Optionally, send an AJAX request to update the 'is_read' status in the database
        });
    });
});
