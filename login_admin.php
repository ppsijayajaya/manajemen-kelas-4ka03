    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" href="style/login.css" />
        <style>
            .section-title {
        color: #326ff3;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
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
session_start(); // Start the session

$message = ""; // Variable to display success/error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'user_auth');

    if ($conn->connect_error) {
        $message = "<span class='error'>Koneksi gagal: " . $conn->connect_error . "</span>";
    } else {
        // Retrieve form data
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // Verify credentials and role
        $sql = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                // Extract the part of email before '@'
                $username = strstr($email, '@', true);
            
                // Successful login: Set session variables
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['username'] = $username; // Store extracted username
                $_SESSION['role'] = 'admin'; // Store role for verification
            
                // Redirect to admin dashboard
                header("Location: admin/index.php");
                exit();
            }elseif (password_verify($password, $user['password'])) {
                // Extract the part of email before '@'
                $username = strstr($email, '@', true);
            
                // Successful login: Set session variables
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['username'] = $username; // Store extracted username
                $_SESSION['role'] = 'admin'; // Store role for verification
            
                // Redirect to admin dashboard
                header("Location: admin/index.php");
                exit();
            } 
            else {
                $message = "<span class='error'>Password salah!</span>";
            }            
        } else {
            $message = "<span class='error'>Email tidak ditemukan atau bukan admin!</span>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>


    <div class="header">Welcome to 4KA03 Authentication System</div>

    <!-- Login Section -->
    <div class="container">
        <div class="section-title">Admin Login</div>
        <form action="login_admin.php" method="POST">
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
                <button type="submit">Login</button>
                <a href="login.php">User Login</a>
            </div>

            <!-- Tampilkan pesan sukses/gagal -->
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            </form>
        </div>
    </body>
    </html>
