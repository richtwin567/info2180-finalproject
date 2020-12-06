import { updateIssue } from "./issues.js";
// This module handles how the data is displayed on the client side

/**
 * Handles the toggling between the various segments of the issue tracker
 */
function addContentListeners() {
	var filters = document.getElementsByClassName("filter-option");
	
	for (var filter of filters) {
		filter.addEventListener("click",e=>{
			var activeFilter = document.getElementById("active-filter");
			activeFilter.id = "";
			e.target.id = "active-filter";
			loadDynamicContent();
		})
	}

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
			loadDynamicContent();
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
	loadDynamicContent();
}


function loadDynamicContent() {
	fetchAllUsersForForm();
	var filters = document.getElementsByClassName("filter-option");
	var query;
	for (var filter of filters) {
		if (filter.id == "active-filter") {
			query = filter.getAttribute("data-query");
			if (filter.innerHTML == "MY TICKETS") {
				fetch("./server/server.php/session")
					.then((res) => res.json())
					.then((data) => (query += data["id"]))
					.then((_) => fetchAllIssuesForTable(query));
			} else {
				fetchAllIssuesForTable(query);
			}
		}
	}
}

function buildUserOption(user) {
	return `
    <option value="${user["id"]}">${user["firstname"]} ${user["lastname"]}</option>`;
}

function buildIssueRow(issue) {
	return `
    <tr>
        <td>#${issue["id"]} <a data-link="${issue["id"]}" class="issue">${
		issue["title"]
	}</a></td>
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

function fetchAllIssuesForTable(filter = null) {
	var url = "./server/server.php/issues";
	if (filter != null) {
		url += filter;
	}
	fetch(url).then((res) => {
		if (res.status == 200) {
			res.json()
				.then((data) => {
					const table = document.getElementById("issues-table-body");
					table.innerHTML = "";
					for (var record of data) {
						table.innerHTML += buildIssueRow(record);
					}
					return data;
				})
				.then((data) => {
					const links = document.querySelectorAll(
						"#issues-table-body a"
					);
					console.log(links);
					for (let i = 0; i < links.length; i++) {
						const el = links[i];
						el.addEventListener("click", (e) =>
							showDetails(data[i])
						);
					}
				});
		}
	});
}

function showDetails(data) {
	console.log(data);
	let allContent = document.querySelectorAll(".content");
	const id = document.getElementById("issue-id");
	const title = document.getElementById("job-title");
	const desc = document.getElementById("issue-desc-con").children[0];
	const created = document.getElementsByClassName("issue-update")[0];
	const updated = document.getElementsByClassName("issue-update")[1];
	const assignee = document.getElementById("issue-assignee");
	const type = document.getElementById("issue-type");
	const priority = document.getElementById("issue-priority");
	const status = document.getElementById("job-status");

	//put the correct content on the page for the issue
	id.innerHTML = "Issue #" + data["id"];
	title.innerHTML = data["title"];
	desc.innerHTML = data["description"];
	var date = new Date(data["created"]);
	var dateFormatted = new Intl.DateTimeFormat("en-US", {
		dateStyle: "full",
		timeStyle: "short",
	}).format(date);
	created.innerHTML =
		"Issue created on " + dateFormatted + " by " + data["created_by"];
	var date = new Date(data["created"]);
	var dateFormatted = new Intl.DateTimeFormat("en-US", {
		dateStyle: "full",
		timeStyle: "short",
	}).format(date);
	updated.innerHTML = "Last update on " + dateFormatted;
	assignee.innerHTML = data["assigned_to"];
	type.innerHTML = data["type"];
	priority.innerHTML = data["priority"];
	status.innerHTML = data["status"];

	//show the details and hide the other content
	for (let content of allContent) {
		// Set the appropriate section to be displayed
		if (parseInt(content.getAttribute("data-number")) === 4) {
			content.style.display = "block";
		} else {
			content.style.display = "none";
		}
	}
}

const markClosedBtn = document.getElementById("close-issue");
markClosedBtn.addEventListener("click", (e) => {
	const id = parseInt(
		document.getElementById("issue-id").innerHTML.split("#")[1]
	);
	var url = `./server/server.php/issues?id=${id}`;
	updateIssue(url, "CLOSED").then((res) => {
		if (res.status == 200) {
			fetch(url)
				.then((res) => res.json())
				.then((data) => showDetails(data[0]));
		}
	});
});

const markInProgressBtn = document.getElementById("in-progress-issue");
markInProgressBtn.addEventListener("click", (e) => {
	const id = parseInt(
		document.getElementById("issue-id").innerHTML.split("#")[1]
	);
	var url = `./server/server.php/issues?id=${id}`;
	updateIssue(url, "IN PROGRESS").then((res) => {
		if (res.status == 200) {
			fetch(url)
				.then((res) => res.json())
				.then((data) => showDetails(data[0]));
		}
	});
});

export { addContentListeners, loadContent };
