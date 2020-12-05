// This module handles how the data is displayed on the client side

/**
 * Handles the toggling between the various segments of the issue tracker
 */
function serveContent() {
    let sidebarOptions = document.querySelectorAll('.sidebar-option');
    for (let option of sidebarOptions) {
        option.addEventListener('click', function(event) {
            const eventTarget = event.target;
            const active = document.querySelector(".active");

            // Remove active class from all elements
            if (active) {
                active.classList.remove('active');
            }

            // Adding the active class to the current selected element
            eventTarget.classList.add('active');
            let allContent = document.querySelectorAll('.content');

            // Loop through all content classes
            for (let content of allContent) {

                // Set the appropriate section to be displayed
                if (content.getAttribute('data-number') === option.getAttribute('data-number')) {
                    content.style.display = "block";
                } else {
                    content.style.display = "none";
                }
            }
        });
    }
}

export { serveContent }