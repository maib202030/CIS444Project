const form = document.getElementById("forgot_password");
const pass1input = document.getElementById("newpass1");
const pass2input = document.getElementById("newpass2");

form.addEventListener("submit", function(event) {
    const pass1 = pass1input.value.trim();
    const pass2 = pass2input.value.trim();
    event.preventDefault(); //prevent form from submitting

    if(pass1 != pass2)
        alert("Passwords don't match. Please try again.");
    else if (pass1.length < 6)
        alert("Password must be at least 6 characters long!");
    else if (pass1 == "" || pass2 == "")
        alert("Password cannot be empty!");
    else //if all good, direct to login page
        window.location.href = "login.html";
})