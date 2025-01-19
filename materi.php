<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi</title>
    <link rel="stylesheet" href="style/materi.css" />
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
        <h2>Materi</h2>
        <button class="tab-button">MATKUL</button>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Matkul</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Sistem Terdistribusi</td>
                    <td><a href="route/sistem_terdistribusi.php" class="action-link">Open</a></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Pengelolaan Proyek Sistem Informasi</td>
                    <td><a href="route/pengelolaan_proyek_sistem_informasi.php" class="action-link">Open</a></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Sistem Penunjang Keputusan</td>
                    <td><a href="route/sistem_penunjang_keputusan.php" class="action-link">Open</a></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Analisis Kinerja Sistem</td>
                    <td><a href="route/analisis_kinerja_sistem.php" class="action-link">Open</a></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Sistem Multimedia</td>
                    <td><a href="route/sistem_multimedia.php" class="action-link">Open</a></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>B. Ingg Bisnis 1</td>
                    <td><a href="route/Bahasa_Ingg_Bisnis_1.php" class="action-link">Open</a></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Testing & Implementasi</td>
                    <td><a href="route/Testing_Dan_Implementasi.php" class="action-link">Open</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
