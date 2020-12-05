// This module handles how the data is displayed on the client side

/**
 * Handles the toggling between the various segments of the issue tracker
 */
function addContentListeners() {
	let sidebarOptions = document.querySelectorAll(".sidebar-option");
	for (let option of sidebarOptions) {
		option.addEventListener("click", function (event) {
            const eventTarget = event.target;
            console.log(eventTarget);
			const active = document.querySelector(".active");

			// Remove active class from all elements
			if (active) {
				active.classList.remove("active");
			}

			// Adding the active class to the current selected element
			eventTarget.classList.add("active");
			let allContent = document.querySelectorAll(".content");

			fetch("./server/server.php/session").then((res) => {
				if (res.status == 404) {
					for (let content of allContent) {
						if (
							parseInt(content.getAttribute("data-number")) === 0
						) {
							content.style.display = "block";
						} else {
							content.style.display = "none";
						}
					}
				} else if (res.status == 200) {
					for (let content of allContent) {
						// Set the appropriate section to be displayed
						if (
							content.getAttribute("data-number") ===
							option.getAttribute("data-number")
						) {
							content.style.display = "block";
						} else {
							content.style.display = "none";
						}
					}
				}
			});
		});
	}
}

function loadContent() {
    let allContent = document.querySelectorAll(".content");
    const active = document.querySelector(".active");
    console.log(active);
    

	fetch("./server/server.php/session").then((res) => {
		if (res.status == 404) {
			for (let content of allContent) {
				if (parseInt(content.getAttribute("data-number")) === 0) {
					content.style.display = "block";
				} else {
					content.style.display = "none";
				}
			}
		} else if (res.status == 200) {
			for (let content of allContent) {
				// Set the appropriate section to be displayed
				if (
					content.getAttribute("data-number") ===
					active.getAttribute("data-number")
				) {
					content.style.display = "block";
				} else {
					content.style.display = "none";
				}
			}
		}
	});
}

export { addContentListeners, loadContent };
