<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Register Dulu Cuy</title>
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
            }

            .header {
                background-color: #4ca9a3;
                color: white;
                padding: 50px 20px;
                font-size: 24px;
                text-align: center;
                border-bottom-left-radius: 100px;
            }

            .container {
                background-color: white;
                max-width: 400px;
                margin: -40px auto 20px;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            }

            .section-title {
                color: #326ff3;
                font-size: 20px;
                font-weight: bold;
                text-align: center;
                margin-bottom: 20px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-size: 14px;
            }

            .form-group input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
            }

            .button-group {
                display: flex;
                justify-content: space-between;
                gap: 10px;
            }

            .button-group button,
            .button-group a {
                background-color: #326ff3;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                text-align: center;
                text-decoration: none;
                width: 48%;
            }

            .button-group button:hover,
            .button-group a:hover {
                background-color: #285bb5;
            }

            .message {
                text-align: center;
                margin-top: 20px;
                font-size: 16px;
                color: green;
            }

            .error {
                color: red;
            }
        </style>
    </head>
    <body>
        <?php
        $message = ""; // Variabel untuk menampilkan pesan sukses/gagal

        // Proses form ketika dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Koneksi ke database
            $conn = new mysqli('localhost', 'root', '', 'user_auth');

            if ($conn->connect_error) {
                $message = "<span class='error'>Koneksi gagal: " . $conn->connect_error . "</span>";
            } else {
                // Ambil data dari form
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

                // Periksa apakah email sudah terdaftar
                $checkEmailSql = "SELECT * FROM users WHERE email = '$email'";
                $result = $conn->query($checkEmailSql);

                if ($result->num_rows > 0) {
                    // Email sudah terdaftar
                    $message = "<span class='error'>Anda sudah mendaftar dengan akun ini sebelumnya.</span>";
                } else {
                    // Jika email belum terdaftar, lakukan insert
                    $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

                    if ($conn->query($sql) === TRUE) {
                        $message = "Registrasi berhasil!";
                    } else {
                        $message = "<span class='error'>Error: " . $conn->error . "</span>";
                    }
                }

                $conn->close();
            }
        }
        ?>
        <div class="header">Welcome to 4KA03 Authentication System</div>
        <!-- Register Section -->
        <div class="container">
            <div class="section-title">Register</div>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input
                        type="email"
                        id="login-email"
                        name="email"
                        placeholder="Enter your email"
                        required />
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input
                        type="password"
                        id="login-password"
                        name="password"
                        placeholder="Enter your password"
                        required />
                </div>
                <div class="button-group">
                    <button type="submit">Register</button>
                    <a href="login.php">Yuk Login</a>
                </div>

            <!-- Tampilkan pesan sukses/gagal -->
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            </form>
        </div>
    </body>
</html>
