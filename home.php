<?php
session_start();

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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="style/stylee.css" />
</head>
<body>
    <div class="header">
        <div class="title">4KA03</div>
        <div class="logout" onclick="window.location.href='logout.php'" 
             style="background-color: #d9534f; color: white; font-weight: bold; padding: 10px 15px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;">
            Logout
        </div>    
    </div>

    <div class="main">
        <h2>Beranda</h2>

        <!-- Section 1: Jadwal Kuliah -->
        <div class="section">
            <div class="section-title">Section 1</div>
            <div class="schedule">Jadwal Kuliah</div>
            <img src="Assets/jadual.jpg" alt="Jadwal Kuliah" />
        </div>

        <!-- Section 2: News -->
        <div class="section">
            <div class="section-title">Section 2</div>
            <div class="schedule">News</div>
            <img src="Assets/kel.png" alt="News Image" />
        </div>

        <!-- Links Section -->
        <div class="links">
            <a href="https://baak.gunadarma.ac.id/">BAAK</a>
            <a href="https://v-class.gunadarma.ac.id/">VCLASS</a>
            <a href="https://praktikum.gunadarma.ac.id/login/index.php">Praktikum</a>
            <a href="https://studentsite.gunadarma.ac.id/index.php/site/index">Studentsite</a>
            <a href="https://vm.lepkom.gunadarma.ac.id/">Lepkom</a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="Assets/avatar.png" alt="avatar" style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; margin-left: 70px;">
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
</body>
</html>
