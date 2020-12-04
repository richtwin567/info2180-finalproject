var registrationArea = docment.getElementById('register-user');
var issueCreationArea = document.getElementById('issue-creation');
var homeArea = document.getElementById('issues');

document.addEventListener('load', function() {
    console.log('We are in operation guys');

});

function toggleScreen(event) {
    if (event.target && event.target.nodeName == "LI") {
        let selectedElement = e.target.id;
        if (selectedElement === 'home') {

        } else if (selectedElement === 'add-user') {

        } else if (selectedElement === 'add-issue') {

        } else if (selectedElement === 'logout') {

        }
    }
}

function toggleVisibility(targetElement) {
    if (targetElement.classList.contains('hidden')) {
        targetElement.classList.remove('hidden');
    } else {
        targetElement.classList.add('hidden');
    }
}