document.addEventListener("DOMContentLoaded", () => {
    const passwordInputs = document.querySelectorAll(".show_password");
    const toggleButton = document.getElementById("togglePassword");

    if (passwordInputs.length === 0 || !toggleButton) return;

    toggleButton.src = toggleButton.getAttribute("show-src");

    toggleButton.addEventListener("click", () => {
        
        passwordInputs.forEach((passwordInput) => {
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        });

        const isPasswordVisible = passwordInputs[0].type === "text";
        toggleButton.src = isPasswordVisible
            ? toggleButton.getAttribute("hidden-src")
            : toggleButton.getAttribute("show-src");
    });
});

