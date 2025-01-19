const uploadArea = document.getElementById("upload-area");
const fileInput = document.getElementById("file-input");
const fileNameDisplay = document.getElementById("file-name");  // Elemen untuk menampilkan nama file

uploadArea.addEventListener("click", () => {
    fileInput.click();
});

fileInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Menampilkan nama file yang dipilih
    fileNameDisplay.textContent = `File selected: ${file.name}`;

    // Menambahkan file ke daftar jika diperlukan
    const row = document.createElement("tr");
    row.innerHTML = `
        <td>${++fileCounter}</td>
        <td>${file.name}</td>
        <td>
            <a href="#" class="action-link open">Open</a>
            <a href="#" class="action-link download">Download</a>
            <a href="#" class="action-link delete">Delete</a>
        </td>
        <td><button class="chat-button" onclick="openChat()">Chat</button></td>
    `;
    fileList.appendChild(row);
});
