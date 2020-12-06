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

			console.log("works1");
			fetchAllIssuesForTable();
			console.log("works2");
			fetchAllUsersForForm();
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
	fetchAllIssuesForTable();
	fetchAllUsersForForm();
}

function buildUserOption(user) {
	return `
    <option value="${user["id"]}">${user["firstname"]} ${user["lastname"]}</option>`;
}

function buildIssueRow(issue) {
	return `
    <tr>
        <td>#${issue["id"]} <a data-link="${
		issue["id"]
	}" href="" class="issue">${issue["title"]}</a></td>
        <td>${issue["type"]}</td>
        <td><p class="${issue["status"].split(" ").join("-")}">${
		issue["status"]
	}</p></td>
        <td>${issue["assigned_to"]}</td>
        <td>${issue["created"]}</td>
    </tr>`;
}

function fetchAllUsersForForm() {
	var url = "./server/server.php/users";
	fetch(url).then((res) => {
		if (res.status == 200) {
			res.json().then((data) => {
				const assignable = document.getElementById("assigned_to");
				assignable.innerHTML = "";
				console.log(assignable);
				console.log(data);
				for (var record of data) {
					assignable.innerHTML += buildUserOption(record);
				}
			});
		}
	});
}

function fetchAllIssuesForTable() {
	var url = "./server/server.php/issues";
	fetch(url).then((res) => {
		if (res.status == 200) {
			res.json().then((data) => {
				const table = document.getElementById("issues-table-body");
				table.innerHTML = "";
				for (var record of data) {
					table.innerHTML += buildIssueRow(record);
				}
			});
		}
	});
}

export { addContentListeners, loadContent };
