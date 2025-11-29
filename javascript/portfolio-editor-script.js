document.addEventListener("DOMContentLoaded", function () {
  //  Get portfolioId from URL
  // const params = new URLSearchParams(window.location.search);
  // const portfolioId = params.get("portfolioId");

  // if (!portfolioId) {
  //   alert("No portfolio selected.");
  //   return;
  // }

  const params = new URLSearchParams(window.location.search);
  let portfolioId = params.get("portfolioId");

  // DEV FALLBACK
  if (!portfolioId) {
    console.warn("No portfolioId in URL, using hardcoded value for testing");
    portfolioId = 3;
  }

  // Navbar
  document.getElementById("navbar-settings")?.addEventListener("click", () => {
    window.location.href = "../html/settings.html";
  });

  document.getElementById("navbar-logout")?.addEventListener("click", () => {
    window.location.href = "../html/home.html";
  });

  // Helpers
  async function postJSON(url, data) {
    const res = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    if (!res.ok) throw new Error("Server error");
    return res.json();
  }

  function setupForm(formId, endpoint, fields) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const payload = { portfolioId };

      fields.forEach((field) => {
        payload[field] = document.getElementById(field).value.trim();
      });

      try {
        const result = await postJSON(endpoint, payload);
        if (result.success) {
          alert(`${formId} saved successfully`);
        } else {
          alert(result.error || "Save failed");
        }
      } catch (err) {
        console.error(err);
        alert("Request failed. Check console.");
      }
    });

    form.querySelectorAll("button[type='reset']").forEach((btn) =>
      btn.addEventListener("click", () => {
        fields.forEach((field) => {
          document.getElementById(field).value = "";
        });
      })
    );
  }

  // Forms
  setupForm("AboutMeSection", "../php/edit_aboutme.php", ["tagline", "bio"]);

  setupForm("ProjectsSection", "../php/add_project.php", [
    "projectTitle",
    "description",
    "projectLinks",
  ]);

  // NOTE: This assumes add_skills.php splits text into rows
  setupForm("SkillsSection", "../php/add_skills.php", [
    "keyskills",
    "hardskills",
    "softskills",
  ]);

  // Resume Section
  const resumeForm = document.getElementById("ResumeSection");
  const resumeDisplay = document.querySelector(".resume-display");

  async function loadResume() {
    try {
      const res = await fetch(
        `../php/get_resume.php?portfolioId=${portfolioId}`
      );
      if (!res.ok) throw new Error("Failed fetch");

      const data = await res.json();

      if (data.exists) {
        resumeDisplay.innerHTML = `
          <div class="resume-card">
            <h2>Resume File</h2>
            <p><strong>Name:</strong> ${data.fileName}</p>
            <p><strong>Type:</strong> ${data.fileType}</p>
            <p><strong>Uploaded:</strong> ${new Date(
              data.uploadDate
            ).toLocaleString()}</p>
            <a href="${data.filePath}" target="_blank">Download Resume</a>
          </div>
        `;
      } else {
        resumeDisplay.innerHTML = `
          <div class="resume-card">
            <p>No resume attached yet.</p>
          </div>
        `;
      }
    } catch (err) {
      console.error(err);
      resumeDisplay.innerHTML = "<p>Error loading resume</p>";
    }
  }

  if (resumeForm) {
    loadResume();

    resumeForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const fileInput = document.getElementById("uploadresume");
      if (!fileInput.files.length) {
        alert("Select a resume file");
        return;
      }

      const formData = new FormData();
      formData.append("portfolioId", portfolioId);
      formData.append("resume", fileInput.files[0]);

      try {
        const res = await fetch("../php/upload_resume.php", {
          method: "POST",
          body: formData,
        });

        if (!res.ok) throw new Error("Upload failed");

        const data = await res.json();
        if (data.success) {
          alert("Resume uploaded");
          loadResume();
        } else {
          alert(data.error || "Upload failed");
        }
      } catch (err) {
        console.error(err);
        alert("Resume upload error");
      }
    });
  }

  // Footer Buttons
  document
    .getElementById("newportfolio")
    ?.addEventListener("click", async (e) => {
      e.preventDefault();
      const data = await postJSON("../php/create_portfolio.php", { userId: 2 });
      if (data?.newPortfolioId) {
        window.location.href = `portfolio-editor.html?portfolioId=${data.newPortfolioId}`;
      }
    });

  document
    .getElementById("deleteportfolio")
    ?.addEventListener("click", async (e) => {
      e.preventDefault();
      if (!confirm("Delete this portfolio?")) return;

      const data = await postJSON("../php/delete_portfolio.php", {
        portfolioId,
      });

      if (data.success) {
        alert("Portfolio deleted");
        window.location.href = "../html/home.html";
      }
    });
});
