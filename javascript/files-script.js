document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("search-input");
  const sortSelect = document.getElementById("sort-select");
  const fileList = document.getElementById("file-list");

  let files = [];

  async function loadFiles() {
    try {
      const res = await fetch("../php/get_files.php");

      const data = await res.json();

      if (!data.success) {
        throw new Error(data.error);
      }

      files = data.files;
      renderFiles();

    } catch (err) {
      console.error("Error loading files:", err);
      fileList.innerHTML = "<p style='color:red;'>Unable to load files.</p>";
    }
  }

  function renderFiles() {
    const term = searchInput.value.toLowerCase();

    let filtered = files.filter(f =>
      f.title.toLowerCase().includes(term)
    );

    const sort = sortSelect.value;

    filtered.sort((a, b) => {
      if (sort === "az") return a.title.localeCompare(b.title);
      if (sort === "za") return b.title.localeCompare(a.title);
      if (sort === "newest") return new Date(b.createdDate) - new Date(a.createdDate);
      if (sort === "oldest") return new Date(a.createdDate) - new Date(b.createdDate);
    });

    fileList.innerHTML = filtered.length
      ? filtered.map(
        f => `
        <div class="file-card">
          <h3>${f.title}</h3>
          <p>Created: ${new Date(f.createdDate).toLocaleDateString()}</p>
          <a href="../html/portfolio.html?id=${f.portfolioId}">Open</a>
        </div>`
      ).join("")
      : "<p>No saved portfolios.</p>";
  }

  searchInput.addEventListener("input", renderFiles);
  sortSelect.addEventListener("change", renderFiles);

  loadFiles();
});
