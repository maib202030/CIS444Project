const form = document.getElementById("signup_form");
const passwordInput = document.getElementById("input_password");
const confirmInput = document.getElementById("confirm");

form.addEventListener("submit", function(event) {
    const password = passwordInput.value.trim();
    const confirm = confirmInput.value.trim();
    event.preventDefault(); //prevent form from submitting

    if (password.length < 6) //can be changed later to account for characters and such
        alert("Password must be at least 6 characters long!");
    else if (password === "")
        alert("Password cannot be empty!");
    else if(password != confirm)
        alert("Passwords don't match. Please try again.")
    else {
        // If all good, redirect to login.html
        window.location.href = "login.html";
    }
})