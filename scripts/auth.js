//  This module handles the authentication of users in the application
import { loadContent } from "./display.js";

const loginForm = document.getElementById("login-user");

loginForm.addEventListener("submit", (e) => {
	e.preventDefault();
	const email = document.getElementById("login-email").value;
	const pass = document.getElementById("login-password").value;
	var url = `./server/server.php/users?email=${email}&password=${pass}`;
	fetch(url)
		.then((res) => {
			if (res.status == 200) {
				loadContent();
			}
		})

		//This will allow you to fetch the data of the currently logged in user from the session
		//Not needed in this function though. Testing only.
		.then((_) => {
			fetch("./server/server.php/session")
				.then((res) => res.text())
				.then((data) => console.log(data));
		});
});

const logoutBtn = document.getElementById("logout");
logoutBtn.addEventListener("click", (e) => {
	fetch("./server/server.php/session", {
		method: "DELETE",
	}).then((_) => {
		loadContent();
	});
});

function authenticateUser() {
	let submitBtn = document.getElementById("");
}

function registerUser(e) {
	e.preventDefault();
	let formElements = document.getElementById("register-user").elements;
	console.log(formElements);
}

export { registerUser };
