<<<<<<< HEAD
import { fetchissues } from './issues.js';

document.addEventListener('load', function() {
    console.log('We are in operation guys');
    const homeBtn = document.querySelector('#home');
    const addUserBtn = document.querySelector('#add-user');
    const addIssueBtn = document.querySelector('#add-issue');
    const logoutBtn = document.querySelector('#logout')
});
=======
// The main driver for the application. All function calls are made to this module. 
import { serveContent } from './display.js';

window.addEventListener('load', function() {
    serveContent();
});
>>>>>>> 2556bc05af3d2d35225617b6ec551d21b40cee8c
