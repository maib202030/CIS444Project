/**after db and authentication, whether the user is new or experienced
 * will display a different home page
 */

// --- Toggle Dark Mode ---
const themeToggle = document.getElementById("theme-toggle");

themeToggle.addEventListener("change", () => {
  document.body.classList.toggle("dark-mode", themeToggle.checked);
});


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