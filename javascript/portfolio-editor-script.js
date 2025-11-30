document.addEventListener("DOMContentLoaded", async function () {
  // USER / SESSION LOGIC
  try {
    const res = await fetch("../php/get_user.php");
    if (!res.ok) throw new Error("Session check failed");

    const user = await res.json();

    // if (!user.loggedIn) {
    //   window.location.href = "../html/login.html";
    //   return;
    // }

    const greeting = document.getElementById("user-greeting");
    if (greeting) {
      greeting.textContent = `Hello ${user.name} 👋`;
    }
  } catch (err) {
    console.error("User session error:", err);
    // window.location.href = "../html/login.html";
    return;
  }

  // PORTFOLIO ID LOGIC
  const params = new URLSearchParams(window.location.search);
  let portfolioId = params.get("portfolioId");

  // DEV fallback
  if (!portfolioId) {
    console.warn("No portfolioId in URL, using fallback");
    portfolioId = 3;
  }

  // NAVBAR
  document.getElementById("navbar-settings")?.addEventListener("click", () => {
    window.location.href = "../html/settings.html";
  });

  document
    .getElementById("navbar-logout")
    ?.addEventListener("click", async () => {
      await fetch("../php/logout.php");
      window.location.href = "../html/login.html";
    });

  // HELPERS
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
      fields.forEach((f) => {
        payload[f] = document.getElementById(f).value.trim();
      });

      try {
        const result = await postJSON(endpoint, payload);
        if (result.success) alert(`${formId} saved successfully`);
        else alert(result.error || "Save failed");
      } catch (err) {
        console.error(err);
        alert("Request failed");
      }
    });

    form
      .querySelectorAll("button[type='reset']")
      .forEach((btn) =>
        btn.addEventListener("click", () =>
          fields.forEach((f) => (document.getElementById(f).value = ""))
        )
      );
  }

  // FORMS

  setupForm("AboutMeSection", "../php/edit_aboutme.php", ["tagline", "bio"]);

  setupForm("ProjectsSection", "../php/add_project.php", [
    "projectTitle",
    "description",
    "projectLinks",
  ]);

  setupForm("SkillsSection", "../php/add_skills.php", [
    "keyskills",
    "hardskills",
    "softskills",
  ]);

  // RESUME
  const resumeForm = document.getElementById("ResumeSection");
  const resumeDisplay = document.querySelector(".resume-display");

  async function loadResume() {
    try {
      const res = await fetch(
        `../php/get_resume.php?portfolioId=${portfolioId}`
      );
      if (!res.ok) throw new Error();

      const data = await res.json();

      resumeDisplay.innerHTML = data.exists
        ? `
          <div class="resume-card">
            <h2>Resume File</h2>
            <p><strong>Name:</strong> ${data.fileName}</p>
            <p><strong>Type:</strong> ${data.fileType}</p>
            <p><strong>Uploaded:</strong> ${new Date(
              data.uploadDate
            ).toLocaleString()}</p>
            <a href="${data.filePath}" target="_blank">Download Resume</a>
          </div>`
        : `<div class="resume-card"><p>No resume attached.</p></div>`;
    } catch {
      resumeDisplay.innerHTML = "<p>Error loading resume</p>";
    }
  }

  if (resumeForm) {
    loadResume();

    resumeForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const file = document.getElementById("uploadresume").files[0];
      if (!file) return alert("Select a resume");

      const fd = new FormData();
      fd.append("portfolioId", portfolioId);
      fd.append("resume", file);

      const res = await fetch("../php/upload_resume.php", {
        method: "POST",
        body: fd,
      });

      const data = await res.json();
      data.success ? loadResume() : alert("Upload failed");
    });
  }

  // FOOTER
  document
    .getElementById("newportfolio")
    ?.addEventListener("click", async (e) => {
      e.preventDefault();
      const data = await postJSON("../php/create_portfolio.php", {});
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
      if (data.success) window.location.href = "../html/home.html";
    });

  // document.getElementById("export-portfolio")?.addEventListener("click", () => {
  //   window.location.href = `../php/export_portfolio.php?portfolioId=${portfolioId}`;
  // });

  document.getElementById("export-portfolio").addEventListener("click", () => {
    const element = document.getElementById("portfolio-container"); // The container to export
    const opt = {
      margin: 0.5, // inches
      filename: "Portfolio.pdf", // file name
      image: { type: "jpeg", quality: 0.98 },
      html2canvas: { scale: 2, useCORS: true },
      jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
    };

    html2pdf().set(opt).from(element).save();
  });
});
