document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("forgot_password");
    const emailInput = document.getElementById("email");
    const pass1 = document.getElementById("newpass1");
    const pass2 = document.getElementById("newpass2");
    const submitBtn = document.getElementById("submitBtn");

    // Create a message area
    let messageBox = document.createElement("p");
    messageBox.id = "forgot-message";
    messageBox.style.marginTop = "15px";
    form.appendChild(messageBox);

    form.addEventListener("submit", async (e) => {
        e.preventDefault(); // stop form from reloading page

        messageBox.textContent = "";
        messageBox.style.color = "black";

        // Basic validation
        const email = emailInput.value.trim();
        const p1 = pass1.value.trim();
        const p2 = pass2.value.trim();

        if (!email || !p1 || !p2) {
            showMessage("Please fill out all fields.", "red");
            return;
        }

        if (!validateEmail(email)) {
            showMessage("Invalid email format.", "red");
            return;
        }

        if (p1 !== p2) {
            showMessage("Passwords do not match.", "red");
            return;
        }

        // Disable button while processing
        submitBtn.disabled = true;
        submitBtn.textContent = "Updating...";

        try {
            const formData = new FormData();
            formData.append("email", email);
            formData.append("newpass1", p1);
            formData.append("newpass2", p2);

            const response = await fetch("../php/forgot_password.php", {
                method: "POST",
                body: formData,
            });


			const raw = await response.text();
			console.log("RAW SERVER RESPONSE:\n\n", raw);

			let data;
			try {
				data = JSON.parse(raw);
			} catch (e) {
				showMessage("Server returned invalid response.", "red");
				return;
			
			}
			
			if (data.success) {
				showMessage(data.message, "green");
				form.reset();
			} else {
				showMessage(data.message, "red");
			}

        } catch (err) {
            showMessage("Server error. Please try again.", "red");
            console.error("Error:", err);
        }

        submitBtn.disabled = false;
        submitBtn.textContent = "Reset Password";
    });

    // Helper functions
    function showMessage(msg, color) {
        messageBox.textContent = msg;
        messageBox.style.color = color;
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});
