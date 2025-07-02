<?php
session_start();

// Jika sudah login, redirect ke display
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: display.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "Lab_7");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = mysqli_real_escape_string($conn, $_POST['matric']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    $sql = "INSERT INTO users (matric, name, password, role) VALUES ('$matric', '$name', '$password', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?registered=1");
        exit;
    } else {
        $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 400px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="">
            <div class="form-group">
                <label>Matric:</label>
                <input type="text" name="matric" required maxlength="10">
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required maxlength="100">
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Role:</label>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="lecturer">Lecturer</option>
                </select>
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>