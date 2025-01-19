<?php
$password = "mypassword123"; // Password asli
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash menggunakan Bcrypt
echo $hashedPassword;
?>