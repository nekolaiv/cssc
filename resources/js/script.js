const togglePasswordElements = document.querySelectorAll('.togglePassword');
const passwordInputs = document.querySelectorAll('.password');

togglePasswordElements.forEach((togglePassword, index) => {
    togglePassword.addEventListener('click', function () {
        const passwordInput = passwordInputs[index];
        const isPasswordVisible = passwordInput.getAttribute('type') === 'text';
        passwordInput.setAttribute('type', isPasswordVisible ? 'password' : 'text');
    });
});


