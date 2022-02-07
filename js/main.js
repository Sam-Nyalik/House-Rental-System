// Password Eye icon toggle
const passwordToggle = document.querySelector('#passwordEyeToggle');
const password = document.querySelector('#password');

// Add an event listener to the passwordToggle
passwordToggle.addEventListener('click', function (e) {
    // Toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    // Toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
})

// Confirm Password Eye Toggle
const confirmPassword = document.querySelector('#confirmPassword');
const confirmPasswordToggle = document.querySelector('#confirmPasswordEyeToggle');

// Add an event listener to the confirmPasswordEyeToggle
confirmPasswordToggle.addEventListener('click', function (e) {
    // Toggle the type attribute
    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPassword.setAttribute('type', type);

    // Toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
})

// New Password Eye Toggle
const newPassword = document.querySelector("#newPassword");
const newPasswordEyeToggle = document.querySelector("#newPasswordEyeToggle");

// Add an event listener to the password toggle
newPasswordEyeToggle.addEventListener("click", function(e) {
    // Toggle the type attribute
    const pwdType = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    newPassword.setAttribute('type', pwdType);

    // Toggle the eye / eye-slash icon
    this.classList.toggle('bi-eye');
})
