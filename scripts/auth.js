//  This module handles the authentication of users in the application

const loginForm = document.getElementById("login-user");

loginForm.addEventListener("submit", (e) => {
	e.preventDefault();
	const email = document.getElementById("login-email").value;
	const pass = document.getElementById("login-password").value;
	var url = `./server/server.php/users?email=${email}&password=${pass}`;
	fetch(url)
		.then((res) => res.text())
		.then((data) => console.log(data));
});
