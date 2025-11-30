document.addEventListener("DOMContentLoaded", () => {

    const adminKeyInput = document.getElementById("adminInput");
    const enterKeyBtn = document.getElementById("adminKeyBtn");

    console.log("adminKeyInput:", adminKeyInput);
    console.log("enterKeyBtn:", enterKeyBtn);


    enterKeyBtn.addEventListener("click", () => {
        const key = adminKeyInput.value.trim();

        if (!key) {
            alert("Please enter the admin key.");
            return;
        }

        fetch("../php/set_user_admin.php", {
            method: "POST",
            body: new URLSearchParams({ adminKey: key })
        })
        .then(res => {
            return res.json();
        })
        .then(data => {
            alert(data.message);

            if (data.success) {
                window.location.href = "./admin.html";
            }
        })
        .catch(err => {
            alert("Error contacting server.");
        });
    });
});
