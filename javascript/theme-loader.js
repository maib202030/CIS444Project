// Load user theme from SQL backend on any page
fetch("../php/get_theme.php")
  .then((res) => res.json())
  .then((data) => {
    if (data.preference === "dark") {
      document.body.classList.add("dark-mode");
    } else {
      document.body.classList.remove("dark-mode");
    }
  });
