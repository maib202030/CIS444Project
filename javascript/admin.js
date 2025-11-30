document.addEventListener("DOMContentLoaded", () => {
    loadUsers();

    document.getElementById("add-user-button").addEventListener("click", addUser);
});

function loadUsers() {
    fetch("../php/get_user.php")
        .then(res => res.json())
        .then(users => renderUserTable(users));
}

function renderUserTable(users) {
    const tableBody = document.getElementById("admin-table-body");
    tableBody.innerHTML = "";

    users.forEach(user => {
        const row = document.createElement("tr");
        row.dataset.id = user.userId;

        row.innerHTML = `
            <td>${user.userId}</td>
            <td class="field name">${user.name}</td>
            <td class="field email">${user.email}</td>
            <td class="field role">${user.role}</td>
            <td class="actions">
                <button class="edit-btn">Edit</button>
                <button class="delete-btn">Delete</button>
            </td>
        `;

        tableBody.appendChild(row);
    });

    attachTableListeners();
}

function attachTableListeners() {
    document.querySelectorAll(".edit-btn").forEach(btn =>
        btn.addEventListener("click", handleEditClick)
    );

    document.querySelectorAll(".delete-btn").forEach(btn =>
        btn.addEventListener("click", handleDeleteClick)
    );
}

function addUser() {
    const name = prompt("Enter username:");
    const email = prompt("Enter email:");
    const password = prompt("Enter password:");

    if (!name || !email || !password) return;

    fetch("../php/add_user.php", {
        method: "POST",
        body: new URLSearchParams({ name, email, password })
    })
        .then(() => loadUsers());
}

function handleDeleteClick(e) {
    const row = e.target.closest("tr");
    const userId = row.dataset.id;

    if (!confirm("Delete this user?")) return;

    fetch("../php/delete_user.php", {
        method: "POST",
        body: new URLSearchParams({ id: userId })
    })
        .then(() => loadUsers());
}

function handleEditClick(e) {
    const row = e.target.closest("tr");
    const btn = e.target;

    if (btn.textContent === "Edit") {
        enterEditMode(row, btn);
    } else {
        saveRowChanges(row, btn);
    }
}

function enterEditMode(row, btn) {
    const fields = row.querySelectorAll(".field");

    fields.forEach(cell => {
        const value = cell.textContent;
        cell.innerHTML = `<input type="text" value="${value}">`;
    });

    btn.textContent = "Save";

    const cancelBtn = document.createElement("button");
    cancelBtn.textContent = "Cancel";
    cancelBtn.className = "cancel-btn";

    row.querySelector(".actions").appendChild(cancelBtn);

    cancelBtn.addEventListener("click", () => loadUsers());
}

function saveRowChanges(row, btn) {
    const userId = row.dataset.id;

    const name = row.querySelector(".name input").value;
    const email = row.querySelector(".email input").value;
    const role = row.querySelector(".role input").value;

    fetch("../php/edit_user.php", {
        method: "POST",
        body: new URLSearchParams({
            id: userId,
            name,
            email,
            role
        })
    })
        .then(() => loadUsers());
}
