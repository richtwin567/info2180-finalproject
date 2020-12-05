// The main driver for the application. All function calls are made to this module. 
import { addContentListeners, loadContent } from './display.js';
import { registerUser } from './auth.js';

window.addEventListener('load', function(e) {
    loadContent();
    addContentListeners();
    let registrationBtn = document.querySelector('#register-user .btn');
    registrationBtn.addEventListener('click', registerUser(e));
});