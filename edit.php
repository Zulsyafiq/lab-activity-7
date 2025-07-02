<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "Lab_7");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$matric = mysqli_real_escape_string($conn, $_GET['matric']);
$sql = "SELECT * FROM users WHERE matric='$matric'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: display.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    $update_sql = "UPDATE users SET name='$name', role='$role' WHERE matric='$matric'";
    
    if (mysqli_query($conn, $update_sql)) {
        header("Location: display.php");
        exit;
    } else {
        $error = "Error updating record: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; margin-right: 10px; }
        .cancel { background: #f44336; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label>Matric:</label>
                <input type="text" value="<?php echo htmlspecialchars($user['matric']); ?>" readonly>
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Role:</label>
                <select name="role" required>
                    <option value="student" <?php echo $user['role'] == 'student' ? 'selected' : ''; ?>>Student</option>
                    <option value="lecturer" <?php echo $user['role'] == 'lecturer' ? 'selected' : ''; ?>>Lecturer</option>
                </select>
            </div>
            <button type="submit">Update User</button>
            <a href="display.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>