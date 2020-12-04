window.addEventListener('load', function() {
    console.log('test');
    let passwordField = document.getElementById('password');
    let passwordStr = `Invalid password: Must contain at least one number and 
      one uppercase and lowercase letter, and at least 8 or more characters`
    passwordField.setCustomValidity(passwordStr);
});