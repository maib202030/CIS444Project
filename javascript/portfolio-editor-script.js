document.addEventListener("DOMContentLoaded", function () {
  const portfolioId = 1; // replace dynamically if needed

  // Settings and logout
  document.getElementById("navbar-settings").addEventListener("click", () => {
    window.location.href = "../html/settings.html";
  });
  document.getElementById("navbar-logout").addEventListener("click", () => {
    window.location.href = "../html/home.html";
  });

  // Helper: post JSON
  async function postJSON(url, data) {
    try {
      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      return res.json();
    } catch (err) {
      console.error(err);
      alert("An error occurred. Check console.");
    }
  }

  // Helper: handle form submission
  function setupForm(formId, endpoint, fields) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const payload = { portfolioId };
      for (const field of fields) {
        const value = document.getElementById(field).value.trim();
        if (!value) {
          alert(`Please enter ${field}`);
          return;
        }
        payload[field] = value;
      }

      const result = await postJSON(endpoint, payload);
      if (result && result.success) alert(`${formId} saved successfully!`);
      else alert(`Failed to save ${formId}`);
    });

    // Reset buttons
    form.querySelectorAll("button[type='reset']").forEach((btn) =>
      btn.addEventListener("click", () => {
        fields.forEach((field) => {
          document.getElementById(field).value = "";
        });
      })
    );
  }

  // About Me
  setupForm("AboutMeSection", "../php/edit_aboutme.php", ["tagline", "bio"]);

  // Projects
  setupForm("ProjectsSection", "../php/add_project.php", [
    "projectTitle",
    "description",
    "projectLinks",
  ]);

  // Skills
  setupForm("SkillsSection", "../php/add_skills.php", [
    "keyskills",
    "hardskills",
    "softskills",
  ]);

  // Resume upload
  const resumeForm = document.getElementById("ResumeSection");
  if (resumeForm) {
    resumeForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const fileInput = document.getElementById("uploadresume");
      if (!fileInput.files.length) return alert("Select a resume file");

      const formData = new FormData();
      formData.append("portfolioId", portfolioId);
      formData.append("resume", fileInput.files[0]);

      try {
        const res = await fetch("../php/upload_resume.php", {
          method: "POST",
          body: formData,
        });
        const data = await res.json();
        if (data.success) alert("Resume uploaded!");
        else alert("Failed to upload resume");
      } catch (err) {
        console.error(err);
        alert("Upload error. Check console.");
      }
    });
  }

  // Footer actions
  document
    .getElementById("newportfolio")
    ?.addEventListener("click", async (e) => {
      e.preventDefault();
      const data = await postJSON("../php/create_portfolio.php", { userId: 2 });
      if (data?.newPortfolioId) {
        alert("New portfolio created!");
        window.location.reload();
      }
    });

  document
    .getElementById("deleteportfolio")
    ?.addEventListener("click", async (e) => {
      e.preventDefault();
      const confirmDelete = confirm("Are you sure?");
      if (!confirmDelete) return;
      const data = await postJSON("../php/delete_portfolio.php", {
        portfolioId,
      });
      if (data?.success) alert("Portfolio deleted!");
    });

  document
    .getElementById("savechanges")
    ?.addEventListener("click", async (e) => {
      e.preventDefault();
      alert(
        "Changes saved! Make sure all sections are submitted individually."
      );
    });

  document.getElementById("designsettings")?.addEventListener("click", (e) => {
    e.preventDefault();
    alert("Design settings not implemented yet.");
  });
});
