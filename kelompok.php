<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Konfigurasi koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$database = "kelompok_db";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pesan aksi
$uploadMessage = '';

// Proses upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $description = $_POST['description'];
    $photo = $_FILES['photo'];

    // Direktori untuk menyimpan file
    $uploadDir = "uploads/photo/";
    $uploadFile = $uploadDir . basename($photo['name']);

    // Validasi file
    if (move_uploaded_file($photo['tmp_name'], $uploadFile)) {
        // Simpan data ke database
        $sql = "INSERT INTO uploads (photo_name, description, photo_path) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $photo['name'], $description, $uploadFile);

        if ($stmt->execute()) {
            $uploadMessage = "File berhasil diupload dan disimpan ke database.";
        } else {
            $uploadMessage = "Gagal menyimpan ke database: " . $conn->error;
        }

        $stmt->close();
    } else {
        $uploadMessage = "Gagal mengupload file.";
    }
}

// Proses hapus file
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "SELECT photo_path FROM uploads WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($photoPath);
    $stmt->fetch();
    $stmt->close();

    if (file_exists($photoPath)) {
        unlink($photoPath); // Hapus file dari server
    }

    $sql = "DELETE FROM uploads WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $uploadMessage = "File berhasil dihapus.";
    } else {
        $uploadMessage = "Gagal menghapus file: " . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Upload</title>
    <link rel="stylesheet" href="style/kelompok.css" />
    <script src="style/script.js" defer></script>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="title">4KA03</div>
        <div class="logout" onclick="window.location.href='login.php'" style="background-color: #d9534f; color: white; font-weight: bold; padding: 10px 15px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Logout</div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="Assets/avatar.png" alt="avatar" style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover;margin-left: 70px;">
        <div class="user-section">
            <br>
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        </div>
        <ul class="menu">
            <li><a href="home.php">Beranda</a></li>
            <li class="dropdown">
                <a href="#">Mata Kuliah</a>
                <ul class="dropdown-menu">
                    <li><a href="materi.php">Materi</a></li>
                    <li><a href="kelompok.php">Kelompok</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h2>Kelompok</h2>

        <!-- Upload Form -->
        <form action="kelompok.php" method="POST" enctype="multipart/form-data" class="upload-container">
            <p><strong>Foto Upload</strong></p>
            <img src="Assets/image.png" alt="Upload icon" style="margin-bottom: 10px; width: 150px; height: 150px;" />
            <div class="upload-area" id="upload-area">
                <p>Click this tab to browse</p>
                <p>Supported Photo formats: JPG, JPEG, PNG, etc</p>
                <input type="file" id="file-input" name="photo" accept="image/*" style="display: none;">
                <p id="file-name" style="color: gray;"></p> <!-- Menampilkan nama file di sini -->
            </div>
            <input type="text" name="description" class="description-input" placeholder="Description" />
            <button type="submit" class="upload-button">Upload</button>
        </form>

        <!-- Display Upload Message -->
        <?php if (!empty($uploadMessage)): ?>
            <p style="color: <?php echo (strpos($uploadMessage, 'berhasil') !== false) ? 'green' : 'red'; ?>;">
                <?php echo $uploadMessage; ?>
            </p>
        <?php endif; ?>

        <!-- Uploaded Files Table -->
        <table id="uploaded-files">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Photo</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Koneksi ke database
                $conn = new mysqli('localhost', 'root', '', 'kelompok_db');
                if ($conn->connect_error) {
                    die("Koneksi gagal: " . $conn->connect_error);
                }
            
                // Ambil data dari tabel uploads
                $sql = "SELECT * FROM uploads ORDER BY id ASC";
                $result = $conn->query($sql);
            
                // Tampilkan data di tabel
                if ($result->num_rows > 0) {
                    $index = 1; // Nomor urut dimulai dari 1
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $index++ . "</td>"; // Nomor urut bertambah
                        echo "<td><img src='" . htmlspecialchars($row['photo_path']) . "' class='preview-image' alt='" . htmlspecialchars($row['photo_name']) . "' /></td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>
                            <a href='kelompok.php?delete=" . $row['id'] . "' onclick='return confirm(\"Hapus file ini?\");'>Delete</a> |
                            <a href='" . htmlspecialchars($row['photo_path']) . "' download>Download</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Belum ada file yang diupload.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
