<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}
$uploadDir = '../uploads/materi/sistem_multimedia/';
$message = "";
$failed_message = "";
$deleteMessage = "";

// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Ambil file
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Cek apakah file berhasil diupload
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Simpan informasi file ke database
        $conn = new mysqli('localhost', 'root', '', 'materi_upload');

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO sistem_multimedia (file_name, file_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $fileName, $targetPath);

        if ($stmt->execute()) {
            $message = "File berhasil diupload dan disimpan!";
        } else {
            $failed_message = "Gagal menyimpan informasi file ke database: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        $message = "Gagal mengupload file.";
    }
}

// Delete File
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = new mysqli('localhost', 'root', '', 'materi_upload');

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil file
    $stmt = $conn->prepare("SELECT file_path FROM sistem_multimedia WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filePath);
    $stmt->fetch();
    $stmt->close();

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            // Jika file berhasil dihapus
            $deleteMessage = "File berhasil dihapus.";
        } else {
            // Jika gagal menghapus file
            $deleteMessage = "Gagal menghapus file.";
        }
    } else {
        $deleteMessage = "File tidak ditemukan.";
    }

    // Hapus dari database
    $stmt = $conn->prepare("DELETE FROM sistem_multimedia WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Penghapusan berhasil
        $deleteMessage = "File berhasil dihapus.";
    } else {
        // Penghapusan gagal
        $deleteMessage = "Gagal menghapus file.";
    }

    $stmt->close();
    $conn->close();

    // Redirect ke halaman sistem_multimedia.php setelah file dihapus
    header("Location: sistem_multimedia.php");
    exit(); // pastikan script berhenti setelah pengalihan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi Sistem Multimedia</title>
    <style type="text/css">
        @import "../style/distribusi.css";
    </style>
    <script src="../style/script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <div class="title">4KA03</div>
        <div class="logout" onclick="window.location.href='../materi.php'" style="background-color: orange; color: black; font-weight: bold; padding: 10px 15px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">Previous</div>
    </div>

    <div class="sidebar">
    <img src="../Assets/avatar.png" alt="avatar" style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; margin-left: 70px;">
        <div class="user-section">
            <br>
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        </div>
        <ul class="menu">
            <li><a href="../home.php">Beranda</a></li>
            <li class="dropdown">
                <a href="#">Mata Kuliah</a>
                <ul class="dropdown-menu">
                    <li><a href="../materi.php">Materi</a></li>
                    <li><a href="../kelompok.php">Kelompok</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="main">
        <h2>Sistem Multimedia</h2>
        <form action="sistem_multimedia.php" method="POST" enctype="multipart/form-data">
    <div class="upload-area" id="upload-area">
            <p>Drag & drop files or click to browse</p>
            <p>Supported formats: PDF, PPT, DOC</p>
        <input type="file" id="file-input" name="file" accept=".pdf,.ppt,.doc" style="display: none;">
        <p id="file-name" style="color: gray;"></p> <!-- Menampilkan nama file di sini -->
    </div>

            <div class="upload-container">
                <button class="upload-button">Upload</button>
            </div>
        </form>

        <!-- Tampilkan Pesan -->
        <?php if (!empty($message)): ?>
            <div style="color: green; font-weight: bold; margin-bottom: 15px;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($deleteMessage)): ?>
            <div style="color: red; font-weight: bold; margin-bottom: 15px;">
                <?php echo $deleteMessage; ?>
            </div>
        <?php endif; ?>

        <h2>Daftar File</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama File</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'materi_upload');

                if ($conn->connect_error) {
                    die("Koneksi gagal: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM sistem_multimedia";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$counter}</td>
                            <td>{$row['file_name']}</td>
                            <td>
                                <a href='{$row['file_path']}' download>Download</a>
                                <a href='sistem_multimedia.php?id={$row['id']}'>Delete</a>
                            </td>
                        </tr>";
                        $counter++;
                    }
                } else {
                    echo "<tr><td colspan='3'>Belum ada file yang diupload.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
