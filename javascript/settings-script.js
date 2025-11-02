/**after db and authentication, whether the user is new or experienced
 * will display a different home page
 */

const toggle = document.getElementById("theme-toggle");
toggle.addEventListener("change", () => {
  document.body.classList.toggle("dark-mode", toggle.checked);
});