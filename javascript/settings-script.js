/**after db and authentication, whether the user is new or experienced
 * will display a different home page
 */

// --- Toggle Dark Mode ---
const themeToggle = document.getElementById("theme-toggle");

// Restore saved theme on load
if (localStorage.getItem("theme") === "dark") {
  document.body.classList.add("dark-mode");
  if (themeToggle) themeToggle.checked = true;
}

// Toggle changes, save preference
if (themeToggle) {
  themeToggle.addEventListener("change", () => {
    if (themeToggle.checked) {
      document.body.classList.add("dark-mode");
      localStorage.setItem("theme", "dark");
    } else {
      document.body.classList.remove("dark-mode");
      localStorage.setItem("theme", "light");
    }
  });
}



// --- View Password ---
const passwordField = document.querySelector('#settings-privacy input[type="password"]');
const viewButton = document.querySelector('#settings-privacy li.settings-right:nth-of-type(2) button');

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