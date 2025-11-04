/*
CONSIDERATIONS:
- Unified form submission with users only being able to submit once all sections are complete
*/

document.addEventListener("DOMContentLoaded", function () {
  // settings and logout button
  const settingsBtn = document.getElementById("navbar-settings");
  const logoutBtn = document.getElementById("navbar-logout");

  settingsBtn.addEventListener("click", () => {
    window.location.href = "../html/settings.html";
  });

  logoutBtn.addEventListener("click", () => {
    window.location.href = "../html/home.html";
  });

  // reuable function for all section
  function setupFormValidation(formId, fields, formName) {
    const form = document.getElementById(formId);
    if (!form) return;

    // handle submit
    form.addEventListener("submit", function (event) {
      event.preventDefault();

      for (const field of fields) {
        const value = document.getElementById(field.id).value.trim();
        if (value === "") {
          alert(`Please enter ${field.name}.`);
          return;
        }
      }

      alert(`${formName} submitted successfully!`);
      // form.submit(); // Uncomment when backend is ready
    });

    // handle reset buttons
    const resetButtons = form.querySelectorAll("button[type='reset']");
    resetButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        fields.forEach((field) => {
          document.getElementById(field.id).value = "";
        });
      });
    });
  }

  setupFormValidation(
    "AboutMeSection",
    [
      { id: "tagline", name: "a tagline" },
      { id: "bio", name: "a biography" },
    ],
    "About Me Section"
  );

  setupFormValidation(
    "ProjectsSection",
    [
      { id: "projectTitle", name: "a project title" },
      { id: "description", name: "a description" },
      { id: "projectLinks", name: "a project link" },
    ],
    "Projects Section"
  );

  setupFormValidation(
    "SkillsSection",
    [
      { id: "keyskills", name: "key skills" },
      { id: "hardskills", name: "hard skills" },
      { id: "softskills", name: "soft skills" },
    ],
    "Skills Section"
  );
});
