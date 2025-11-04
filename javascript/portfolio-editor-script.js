// Settings button
document.addEventListener("DOMContentLoaded", function () {
  const settingsBtn = document.getElementById("navbar-settings");
  const logoutBtn = document.getElementById("navbar-logout");

  settingsBtn.addEventListener("click", function () {
    window.location.href = "../html/home.html";
  });

  logoutBtn.addEventListener("click", function () {
    window.location.href = "../html/login.html";
  });
});
