document.addEventListener("DOMContentLoaded", async () => {
  const searchInput = document.getElementById("search-input");
  const sortSelect = document.getElementById("sort-select");
  const fileList = document.getElementById("file-list");

  let files = [];

  //load
  async function loadFiles() {
    try {
      const res = await fetch("../php/get_files.php");
      const data = await res.json();

      if (!data.success) throw new Error(data.error);

      files = data.files;
      renderFiles();
    } catch (err) {
      fileList.innerHTML = "<p>Error loading files.</p>";
      console.error(err);
    }
  }

  function renderFiles() {
    let filtered = files.filter((file) => {
      const term = searchInput.value.toLowerCase();
      return file.fileName.toLowerCase().includes(term);
    });

    const sort = sortSelect.value;

    filtered.sort((a, b) => {
      if (sort === "az") 
        return a.fileName.localeCompare(b.fileName);
      if (sort === "za") 
        return b.fileName.localeCompare(a.fileName);
      if (sort === "newest") 
        return new Date(b.uploadDate) - new Date(a.uploadDate);
      if (sort === "oldest") 
        return new Date(a.uploadDate) - new Date(b.uploadDate);
    });

    fileList.innerHTML = filtered
      .map(
        f => `
        <div class="file-card">
          <h3>${f.fileName}</h3>
          <p>Uploaded: ${new Date(f.uploadDate).toLocaleDateString()}</p>
          <a href="${f.filePath}" target="_blank">Open</a>
        </div>`
      )
      .join("");
  }

  searchInput.addEventListener("input", renderFiles);
  sortSelect.addEventListener("change", renderFiles);

  loadFiles();
});
