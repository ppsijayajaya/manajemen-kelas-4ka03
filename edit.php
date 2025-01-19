<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'user_auth');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // New password entered by admin
    $role = $_POST['role'];

    // If password is entered, hash it before updating
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // If no password is entered, use the current password (or leave it unchanged)
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentUser = $result->fetch_assoc();
        $hashedPassword = $currentUser['password'];  // Keep the existing password
        $stmt->close();
    }

    // Update the user's information in the database
    $stmt = $conn->prepare("UPDATE users SET email = ?, password = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $email, $hashedPassword, $role, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the admin dashboard
    header("Location: index.php");
    exit();
}

$conn->close();
?>
