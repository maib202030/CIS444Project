// Toggle Dark Mode (PHP + Fake DB)
const themeToggle = document.getElementById("theme-toggle");

// Load theme from PHP fake DB
fetch("../php/get_theme.php")
  .then((res) => res.json())
  .then((data) => {
    if (data.preference === "dark") {
      document.body.classList.add("dark-mode");
      if (themeToggle) themeToggle.checked = true;
    }
  });

// Save theme via PHP when toggled
if (themeToggle) {
  themeToggle.addEventListener("change", () => {
    const newTheme = themeToggle.checked ? "dark" : "light";

    document.body.classList.toggle("dark-mode", newTheme === "dark");

    fetch("../php/set_theme.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ preference: newTheme }),
    });
  });
}

// --- View Password ---
const passwordField = document.querySelector(
  '#settings-privacy input[type="password"]'
);
const viewButton = document.querySelector(
  "#settings-privacy li.settings-right:nth-of-type(2) button"
);

if (passwordField && viewButton) {
  viewButton.addEventListener("click", () => {
    if (passwordField.type === "password") {
      passwordField.type = "text";
      viewButton.textContent = "Hide";
    } else {
      passwordField.type = "password";
      viewButton.textContent = "View";
    }
  });
}
