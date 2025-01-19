<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to admin login page if not authenticated
    header("Location: login_admin.php");
    exit();
}
// Database connection
$conn = new mysqli('localhost', 'root', '', 'user_auth');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $userId = $_POST['delete_user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}


// Fetch all users
$result = $conn->query("SELECT * FROM users");
$users = $result->fetch_all(MYSQLI_ASSOC);
$result->close();
$conn->close();

?>
<style>
    .modal {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    width: 400px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.modal-content label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold;
}

.modal-content input,
.modal-content select {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.modal-content button {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.modal-content button[type="submit"] {
    background-color: #4ca9a3;
    color: white;
}

.modal-content button[type="button"] {
    background-color: #d9534f;
    color: white;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    border: 1px solid #ddd;
    text-align: left;
    padding: 10px;
}

table th {
    background-color: #4ca9a3;
    color: white;
    text-align: center;
}

table td {
    text-align: center;
    font-size: 16px;
}

.action-link.open-btn {
    background-color: #326ff3;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.action-link.open-btn:hover {
    background-color: #285bb5;
}

.action-link.edit-btn {
    background-color: #326ff3;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.action-link.edit-btn:hover {
    background-color: #285bb5;
}


</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link rel="stylesheet" href="../style/stylee.css" />
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
        <h2>Admin Dashboard</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['password']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td>
                            <button class="action-link edit-btn" 
                                data-id="<?php echo $user['id']; ?>" 
                                data-email="<?php echo $user['email']; ?>" 
                                data-password="<?php echo $user['password']; ?>" 
                                data-role="<?php echo $user['role']; ?>">
                                Edit
                            </button>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="action-link open-btn" 
                                        onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <form id="editForm" action="edit.php" method="POST">
                <input type="hidden" name="id" id="edit-id">
                <label>Email</label>
                <input type="email" name="email" id="edit-email" required>
                <label>Password</label>
                <input type="password" name="password" id="edit-password"> <!-- Password input is visible for editing -->
                <label>Role</label>
                <select name="role" id="edit-role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <button type="submit">Save Changes</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>


    <script>
        // Edit Button Click
        const editButtons = document.querySelectorAll('.edit-btn');
        const modal = document.getElementById('editModal');
        const editForm = document.getElementById('editForm');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('edit-id').value = button.dataset.id;
                document.getElementById('edit-email').value = button.dataset.email;
                document.getElementById('edit-password').value = ''; // Leave blank for new password input
                document.getElementById('edit-role').value = button.dataset.role;
                modal.style.display = 'flex';
            });
        });

        function closeModal() {
            modal.style.display = 'none';
        }

        // Close modal when clicking outside the form
        window.onclick = function(event) {
            if (event.target === modal) {
                closeModal();
            }
        }

    </script>
<!-- Sidebar -->
<div class="sidebar">
        <img src="../Assets/avatar.png" alt="avatar" style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; margin-left: 70px;">
        <div class="user-section">
            <br>
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        </div>
        <ul class="menu">
            <li><a href="index.php">user</a></li>
        </ul>
    </div>
</body>
</html>