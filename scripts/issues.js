import { simulateClick } from "./util.js";

const issue = document.getElementById("issue-creation");
issue.addEventListener("submit", registerIssue);

function registerIssue(e) {
	e.preventDefault();
	let formElements = document.getElementById("issue-creation").elements;
	console.log(formElements);
	var json = {};
	for (var el of formElements) {
		json[el.name] = el.value;
	}

	console.log(json);
	fetch("./server/server.php/issues", {
		method: "POST",
		body: JSON.stringify(json),
	}).then((res) => {
		if (res.status == 200) {
			for (var el of formElements) {
				if (el.getAttribute("type") != "submit") {
					el.value = "";
				}
			}
			var home = document.getElementById("home");
			simulateClick(home);
		}
	});
}

async function updateIssue(url, newStatus) {
	return await fetch(url, {
		method: "PATCH",
		body: JSON.stringify({ status: newStatus }),
	});
}


export {updateIssue};